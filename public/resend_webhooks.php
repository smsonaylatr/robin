<?php
if (($_GET['key'] ?? '') !== 'AUTO_TRANSFER_SECRET_12345') {
    http_response_code(403);
    die('Unauthorized');
}

header('Content-Type: text/plain; charset=utf-8');
date_default_timezone_set('Europe/Istanbul');

$envPath = __DIR__ . '/../.env';
$dbHost = '127.0.0.1';
$dbName = $dbUser = $dbPass = '';
$webhookUrl = '';

if (file_exists($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) !== 2) continue;
        $key = trim($parts[0]);
        $val = trim($parts[1], " \"'\t\n\r\0\x0B");
        if ($key === 'DB_HOST') $dbHost = $val;
        if ($key === 'DB_DATABASE') $dbName = $val;
        if ($key === 'DB_USERNAME') $dbUser = $val;
        if ($key === 'DB_PASSWORD') $dbPass = $val;
        if ($key === 'MAIN_WEBHOOK_URL') $webhookUrl = $val;
    }
}

try {
    $conn = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB bağlantı hatası: " . $e->getMessage());
}

// Denenecek URL'ler (sırasıyla)
$webhookUrls = [
    $webhookUrl,
    'http://smsonaylatr.com/api/gateway/webhook',
    'http://127.0.0.1/api/gateway/webhook',
];
$webhookUrls = array_unique(array_filter($webhookUrls));

echo "=== WEBHOOK RESEND ===\n";
echo "Tarih: " . date('Y-m-d H:i:s') . "\n";
echo "Denenecek URL'ler:\n";
foreach ($webhookUrls as $i => $url) {
    echo "  " . ($i+1) . ". {$url}\n";
}
echo "\n";

$stmt = $conn->query("
    SELECT id, uye, miktar, note, islemno, durum, tarih 
    FROM parayatir 
    WHERE note LIKE '%gateway_source_user_id:%' 
    AND durum = 1
    AND tarih >= DATE_SUB(NOW(), INTERVAL 48 HOUR)
    ORDER BY id DESC
");

$deposits = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Onaylanmış gateway işlem sayısı (son 48 saat): " . count($deposits) . "\n\n";

$successCount = 0;
$failCount = 0;

foreach ($deposits as $deposit) {
    preg_match('/gateway_source_user_id:(\d+)\|ref:(.+)/', $deposit['note'], $matches);
    if (count($matches) < 3) continue;
    
    $sourceUserId = $matches[1];
    $txn = $matches[2];
    
    $postData = json_encode([
        'source_user_id' => $sourceUserId,
        'amount' => $deposit['miktar'],
        'txn' => $txn,
        'transaction_id' => $deposit['id']
    ]);
    
    echo "--- İşlem ID={$deposit['id']} | source_user_id={$sourceUserId} | amount={$deposit['miktar']} ---\n";
    
    $sent = false;
    foreach ($webhookUrls as $tryUrl) {
        $ch = curl_init($tryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Gateway-Secret: AUTO_TRANSFER_SECRET_12345',
            'Host: smsonaylatr.com'
        ]);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        $primaryIp = curl_getinfo($ch, CURLINFO_PRIMARY_IP);
        curl_close($ch);
        
        echo "  URL: {$tryUrl} → IP: {$primaryIp} → HTTP {$httpCode}";
        
        if ($httpCode >= 200 && $httpCode < 400 && strpos($response, 'Just a moment') === false) {
            echo " ✅ BAŞARILI! Response: " . substr($response ?: '', 0, 200) . "\n";
            $successCount++;
            $sent = true;
            break;
        } else {
            echo " ❌ (Response: " . substr($response ?: '', 0, 150) . ")\n";
        }
    }
    
    if (!$sent) {
        $failCount++;
        echo "  ⚠️ HİÇBİR URL ÇALIŞMADI!\n";
    }
    echo "\n";
}

echo "=============================\n";
echo "Toplam: " . count($deposits) . " | Başarılı: {$successCount} | Başarısız: {$failCount}\n";
