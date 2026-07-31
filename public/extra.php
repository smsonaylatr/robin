<?php
@session_start();
header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');
date_default_timezone_set('Europe/Istanbul');

// Log file path
$logFile = __DIR__ . '/1.txt';
$requestId = bin2hex(random_bytes(8));
$requestStartAt = microtime(true);

// Helper to append to log file
function writeLog($message) {
    global $logFile, $requestId;
    $time = date('Y-m-d H:i:s');
    $entry = "[{$time}][RID:{$requestId}] " . $message . PHP_EOL;
    file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
}

// 1. Log incoming request data
$rawInput = file_get_contents('php://input');
$headers = function_exists('getallheaders') ? getallheaders() : [];
$serverMeta = [
    'method'   => $_SERVER['REQUEST_METHOD'] ?? '',
    'remote'   => $_SERVER['REMOTE_ADDR'] ?? '',
    'xff'      => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '',
    'ua'       => $_SERVER['HTTP_USER_AGENT'] ?? '',
    'referer'  => $_SERVER['HTTP_REFERER'] ?? '',
    'ctype'    => $_SERVER['CONTENT_TYPE'] ?? ($_SERVER['HTTP_CONTENT_TYPE'] ?? ''),
    'uri'      => $_SERVER['REQUEST_URI'] ?? '',
];
writeLog("INCOMING REQUEST → Server: " . json_encode($serverMeta));
writeLog("INCOMING REQUEST → Headers: " . json_encode($headers));
writeLog("INCOMING REQUEST → \$_POST: " . json_encode($_POST, JSON_UNESCAPED_UNICODE));
writeLog("INCOMING REQUEST → Raw Body: " . $rawInput);

try {
    $conn = new PDO(
        "mysql:host=localhost;dbname=robinbet_mysql;charset=utf8mb4",
        "robinbet_mysql",
        "Robinbet3434",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    writeLog("DB Connection failed: " . $e->getMessage());
    // Dökümana göre HER CALLBACK İÇİN 200 dönülmeli
    http_response_code(200);
    echo 'OK';
    $elapsedMs = (int)round((microtime(true) - $requestStartAt) * 1000);
    writeLog("RESPONSE → 200 OK in {$elapsedMs}ms");
    exit;
}

// Load secret from DB
$stmtSecret = $conn->prepare("SELECT setting_value FROM payment_settings WHERE setting_key = 'extra_secret' LIMIT 1");
$stmtSecret->execute();
$secretRow = $stmtSecret->fetch(PDO::FETCH_ASSOC);
$secretKey = $secretRow ? $secretRow['setting_value'] : '0dc93978-17e5-4580-b78e-39fc3e014ee7';

$type        = $_POST['type']        ?? '';
$referenceno = $_POST['referenceno'] ?? '';
$amount      = (float)($_POST['amount'] ?? 0);
$status      = $_POST['status']      ?? '';
$security    = $_POST['security']    ?? '';
$detail      = $_POST['detail']      ?? '';

$shaCheck = sha1($secretKey . $referenceno);
writeLog("SECURITY CHECK → calc={$shaCheck} incoming={$security} ref={$referenceno}");
if ($shaCheck !== $security) {
    writeLog("SECURITY FAILED → ref={$referenceno}");
    // Dökümana göre HER CALLBACK İÇİN 200 dönülmeli
    http_response_code(200);
    echo 'OK';
    exit;
}

function sendTelegram($message, $chat_id) {
    global $logFile;
    $token = '8381428947:AAErIG36G8SZWdNY15Zf210ulIdqVlrnXj4';
    $url   = "https://api.telegram.org/bot{$token}/sendMessage";
    $data  = [
        'chat_id'    => $chat_id,
        'text'       => $message,
        'parse_mode' => 'HTML',
    ];

    // Log outgoing Telegram request
    writeLog("OUTGOING POST → URL: {$url}, Payload: " . json_encode($data, JSON_UNESCAPED_UNICODE));

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Log Telegram response
    writeLog("TELEGRAM RESPONSE → " . $response);

    return $response;
}

function formatSuccessMessage($detail, $amount, $date, $name, $username) {
    return "🟢 <b>Extra Cüzdan Ödeme Bildirimi</b> 🟢\n\n"
         . "💼 <b>Kullanıcı:</b> <i>{$name}</i>\n"
         . "👤 <b>Kullanıcı Adı:</b> <i>{$username}</i>\n"
         . "💰 <b>Miktar:</b> <i>{$amount} TL</i>\n"
         . "🕒 <b>İşlem Zamanı:</b> <i>{$date}</i>\n"
         . "📄 <b>Detay:</b> <i>{$detail}</i>\n\n"
         . "✅ <b>Yatırma İşlemi Başarılı</b> 🎉";
}

function formatFailedMessage($detail, $amount, $date, $name, $username) {
    return "🔴 <b>Extra Cüzdan Ödeme Bildirimi</b> 🔴\n\n"
         . "💼 <b>Kullanıcı:</b> <i>{$name}</i>\n"
         . "👤 <b>Kullanıcı Adı:</b> <i>{$username}</i>\n"
         . "💰 <b>Miktar:</b> <i>{$amount} TL</i>\n"
         . "🕒 <b>İşlem Zamanı:</b> <i>{$date}</i>\n"
         . "📄 <b>Detay:</b> <i>{$detail}</i>\n\n"
         . "❌ <b>Yatırma İşlemi Başarısız</b> ⚠️";
}

/**
 * Affiliate komisyon hesapla ve bakiyeye ekle
 * Alt üyenin yatırımı onaylandığında affiliate'e komisyon yansır
 */
function processAffiliateCommission($conn, $user, $depositAmount) {
    try {
        $affiliateId = (int)($user['bayisi'] ?? 0);
        if ($affiliateId <= 0) {
            writeLog("COMMISSION → User {$user['id']} has no affiliate, skipping");
            return;
        }

        // Affiliate'i bul
        $stmt = $conn->prepare("SELECT id, username, name, bakiye, afforani, aff, telegram_chat_id FROM admin WHERE id = :id AND aff = 1 LIMIT 1");
        $stmt->execute([':id' => $affiliateId]);
        $affiliate = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$affiliate) {
            writeLog("COMMISSION → Affiliate ID {$affiliateId} not found or not an affiliate");
            return;
        }

        $commissionRate = (float)($affiliate['afforani'] ?? 0);
        if ($commissionRate <= 0) {
            writeLog("COMMISSION → Affiliate {$affiliate['username']} commission rate is 0, skipping");
            return;
        }

        // Komisyon hesapla
        $commission = round(($depositAmount * $commissionRate) / 100, 2);
        if ($commission <= 0) {
            return;
        }

        // Affiliate bakiyesine ekle
        $newBalance = (float)$affiliate['bakiye'] + $commission;
        $conn->prepare("UPDATE admin SET bakiye = :bakiye WHERE id = :id")
             ->execute([':bakiye' => $newBalance, ':id' => $affiliateId]);

        writeLog("COMMISSION → Affiliate {$affiliate['username']} earned {$commission} TL ({$commissionRate}% of {$depositAmount} TL). New balance: {$newBalance}");

        // Affiliate'e Telegram bildirimi gönder
        if (!empty($affiliate['telegram_chat_id'])) {
            $username = $user['username'] ?? $user['name'] ?? 'Bilinmiyor';
            $date = date('d.m.Y H:i:s');
            $msg = "💰 <b>Komisyon Bildirimi</b>\n\n"
                 . "👤 <b>Üye:</b> {$username}\n"
                 . "💵 <b>Yatırım:</b> {$depositAmount} TL\n"
                 . "📊 <b>Oran:</b> %{$commissionRate}\n"
                 . "✅ <b>Komisyon:</b> {$commission} TL\n"
                 . "💰 <b>Yeni Bakiye:</b> {$newBalance} TL\n"
                 . "🕒 <b>Tarih:</b> {$date}";
            sendTelegram($msg, $affiliate['telegram_chat_id']);
        }
    } catch (Exception $e) {
        writeLog("COMMISSION → Error: " . $e->getMessage());
    }
}

// Log basic callback info
writeLog("Callback Data: " . json_encode([
    'type'       => $type,
    'referenceno'=> $referenceno,
    'amount'     => $amount,
    'status'     => $status,
    'detail'     => $detail,
], JSON_UNESCAPED_UNICODE));

try {
    if ($type === 'deposit') {
        writeLog("FLOW → deposit start (status={$status}, amount={$amount})");
        $refParts = explode('-', $referenceno);
        $userId   = $refParts[1] ?? null;

        if (!$userId) {
            writeLog("INVALID referenceno format: {$referenceno}");
            throw new Exception("Invalid reference number");
        }

        $stmtPending = $conn->prepare("SELECT id, uye FROM parayatir WHERE islemno = :islemno LIMIT 1");
        $stmtPending->execute([':islemno' => $referenceno]);
        $pendingDeposit = $stmtPending->fetch(PDO::FETCH_ASSOC);

        if ($pendingDeposit && !empty($pendingDeposit['uye'])) {
            $userId = $pendingDeposit['uye']; // Overwrite fake user ID with real user ID
        }

        // Load user
        $stmt = $conn->prepare("SELECT * FROM admin WHERE id = :user_id LIMIT 1");
        $stmt->execute([':user_id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            writeLog("User not found: ID {$userId}");
            throw new Exception("User not found");
        }

        if ($status === 'success' && $amount > 0) {
            // Successful deposit
            $currentBalance = (float)$user['bakiye'];
            $newBalance = $currentBalance + $amount;
            writeLog("BALANCE → before={$currentBalance} amount={$amount} after={$newBalance} userId={$userId}");
            $conn->prepare("UPDATE admin SET bakiye = :bakiye WHERE id = :user_id")
                 ->execute([':bakiye' => $newBalance, ':user_id' => $userId]);

            // Bekleyen işlemi (GatewayApiController tarafından oluşturulmuş olabilir) kontrol et
            $stmtPending = $conn->prepare("SELECT id, note FROM parayatir WHERE islemno = :islemno LIMIT 1");
            $stmtPending->execute([':islemno' => $referenceno]);
            $pendingDeposit = $stmtPending->fetch(PDO::FETCH_ASSOC);

            if ($pendingDeposit) {
                // Kaydı güncelle
                $conn->prepare("UPDATE parayatir SET durum = 1, miktar = :miktar, banka = :banka, aciklama = :aciklama WHERE id = :id")
                     ->execute([
                         ':miktar' => $amount,
                         ':banka' => 'Extra Cüzdan - ' . $detail,
                         ':aciklama' => 'Extra Cüzdan ile para yatırma işlemi başarılı',
                         ':id' => $pendingDeposit['id']
                     ]);
                     
                // Gateway webhook bildirimi
                if ($pendingDeposit['note'] && strpos($pendingDeposit['note'], 'gateway_source_user_id:') !== false) {
                    preg_match('/gateway_source_user_id:(\d+)\|ref:(.+)/', $pendingDeposit['note'], $matches);
                    if (count($matches) >= 3) {
                        $sourceUserId = $matches[1];
                        $txn = $matches[2];
                        
                        $postData = [
                            'source_user_id' => $sourceUserId,
                            'amount' => $amount,
                            'txn' => $txn,
                            'transaction_id' => $pendingDeposit['id']
                        ];
                        
                        $envPath = __DIR__ . '/../.env';
                        $webhookUrl = 'http://localhost'; // Fallback if env is missing
                        if (file_exists($envPath)) {
                            $envLines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                            foreach ($envLines as $line) {
                                if (strpos(trim($line), 'MAIN_WEBHOOK_URL') === 0) {
                                    $webhookUrl = trim(explode('=', $line, 2)[1], " \"'\t\n\r\0\x0B");
                                    break;
                                }
                            }
                        }
                        
                        // Cloudflare bypass: doğrudan sunucu IP'sine bağlan
                        $parsedUrl = parse_url($webhookUrl);
                        $webhookHost = $parsedUrl['host'] ?? '';
                        $webhookPort = ($parsedUrl['scheme'] ?? 'https') === 'https' ? 443 : 80;
                        
                        // Sunucunun gerçek IP'sini .env'den oku veya varsayılanı kullan
                        $originIp = '45.90.99.60';
                        if (file_exists($envPath)) {
                            foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $envLine) {
                                if (strpos(trim($envLine), 'MAIN_ORIGIN_IP') === 0) {
                                    $originIp = trim(explode('=', $envLine, 2)[1], " \"'\t\n\r\0\x0B");
                                    break;
                                }
                            }
                        }
                        
                        $ch = curl_init($webhookUrl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                            'Content-Type: application/json',
                            'Gateway-Secret: AUTO_TRANSFER_SECRET_12345',
                            'Host: ' . $webhookHost
                        ]);
                        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
                        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                        // Cloudflare'ı bypass et: DNS çözümlemesini doğrudan origin IP'ye yönlendir
                        curl_setopt($ch, CURLOPT_RESOLVE, [
                            "{$webhookHost}:{$webhookPort}:{$originIp}"
                        ]);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                        $webhookResponse = curl_exec($ch);
                        $webhookHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        curl_close($ch);
                        
                        writeLog("GATEWAY WEBHOOK → Sent for source_user_id={$sourceUserId}, txn={$txn}, httpCode={$webhookHttpCode}, response: {$webhookResponse}");
                    }
                }
            } else {
                // Kayıt yoksa yeni oluştur
                $conn->prepare("
                    INSERT INTO parayatir
                      (uye, adsoyad, tc, banka, islemno, miktar, tur, aciklama, tarih, durum, bonus, note)
                    VALUES
                      (:uye, :adsoyad, :tc, :banka, :islemno, :miktar, :tur, :aciklama, :tarih, :durum, :bonus, :note)
                ")->execute([
                    ':uye'       => $userId,
                    ':adsoyad'   => $user['name'],
                    ':tc'        => $user['tc'] ?? '',
                    ':banka'     => 'Extra Cüzdan - ' . $detail,
                    ':islemno'   => $referenceno,
                    ':miktar'    => $amount,
                    ':tur'       => 'Extra Cüzdan',
                    ':aciklama'  => 'Extra Cüzdan ile para yatırma işlemi başarılı',
                    ':tarih'     => date('Y-m-d H:i:s'),
                    ':durum'     => 1,
                    ':bonus'     => 0,
                    ':note'      => 'Extra Cüzdan Callback - ' . $detail,
                ]);
            }

            $payment = $conn->prepare("
                SELECT adsoyad, miktar, tarih
                FROM parayatir
                WHERE islemno = :islemno
                LIMIT 1
            ");
            $payment->execute([':islemno' => $referenceno]);
            $payData = $payment->fetch(PDO::FETCH_ASSOC);

            if ($payData) {
                $name = $user['name'] ?? '';
                $username = $user['username'] ?? '';
                $msg = formatSuccessMessage($detail, $payData['miktar'], $payData['tarih'], $name, $username);
                sendTelegram($msg, '7522456622');
                sendTelegram($msg, '7213279050');
            }

            // Affiliate komisyon hesapla ve bakiyeye ekle
            processAffiliateCommission($conn, $user, $amount);

            writeLog("RESULT → deposit success userId={$userId} amount={$amount} newBalance={$newBalance}");

        } else {
            // Failed deposit or zero amount
            writeLog("FLOW → deposit failed path (status={$status}, amount={$amount})");
            $conn->prepare("
                INSERT INTO parayatir
                  (uye, adsoyad, tc, banka, islemno, miktar, tur, aciklama, tarih, durum, bonus, note)
                VALUES
                  (:uye, :adsoyad, :tc, :banka, :islemno, :miktar, :tur, :aciklama, :tarih, :durum, :bonus, :note)
            ")->execute([
                ':uye'       => $userId,
                ':adsoyad'   => $user['name'],
                ':tc'        => $user['tc'] ?? '',
                ':banka'     => 'Extra Cüzdan - ' . $detail,
                ':islemno'   => $referenceno,
                ':miktar'    => $amount,
                ':tur'       => 'Extra Cüzdan',
                ':aciklama'  => 'Extra Cüzdan ile para yatırma işlemi başarısız',
                ':tarih'     => date('Y-m-d H:i:s'),
                ':durum'     => 0,
                ':bonus'     => 0,
                ':note'      => 'Extra Cüzdan Callback Failed - ' . $detail,
            ]);

            $payment = $conn->prepare("
                SELECT adsoyad, miktar, tarih
                FROM parayatir
                WHERE islemno = :islemno
                LIMIT 1
            ");
            $payment->execute([':islemno' => $referenceno]);
            $payData = $payment->fetch(PDO::FETCH_ASSOC);

            if ($payData) {
                $name = $user['name'] ?? '';
                $username = $user['username'] ?? '';
                $msg = formatFailedMessage($detail, $payData['miktar'], $payData['tarih'], $name, $username);
                sendTelegram($msg, '7522456622');
                sendTelegram($msg, '7213279050');
            }

            writeLog("RESULT → deposit failed userId={$userId} status={$status} amount={$amount}");
        }
    } else {
        writeLog("FLOW → unhandled type '{$type}' for ref={$referenceno}");
    }

    // You can add withdraw handling here similarly...

    // Dökümana göre HER CALLBACK İÇİN 200 dönülmeli
    http_response_code(200);
    echo 'OK';

} catch (Exception $e) {
    writeLog("Exception caught: " . $e->getMessage());
    // Dökümana göre HER CALLBACK İÇİN 200 dönülmeli
    http_response_code(200);
    echo 'OK';
    $elapsedMs = (int)round((microtime(true) - $requestStartAt) * 1000);
    writeLog("RESPONSE → 200 OK (exception) in {$elapsedMs}ms");
}
?>
