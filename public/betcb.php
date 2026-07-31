<?php
/**
 * Betedor Callback Endpoint
 * Rakip site için kupon bilgilerini alan ve loglayan endpoint
 */

// Veritabanı bağlantısı - Bu bilgileri kendi veritabanına göre değiştir
$dbHost = 'localhost';
$dbName = 'robinbet_mysql'; // Kendi veritabanı adını yaz
$dbUser = 'robinbet_mysql';  // Kendi kullanıcı adını yaz
$dbPass = 'Robinbet3434';          // Kendi şifreni yaz

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Veritabanı bağlantı hatası']);
    exit();
}

// Hata raporlamayı aç
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Gelen istekleri logla
$logFile = __DIR__ . '/logs/betcb_' . date('Y-m-d') . '.log';
$logDir = dirname($logFile);

// Log klasörü yoksa oluştur
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

// Gelen isteği logla
$requestData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'method' => $_SERVER['REQUEST_METHOD'],
    'headers' => getallheaders(),
    'get_params' => $_GET,
    'post_params' => $_POST,
    'raw_input' => file_get_contents('php://input'),
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
    'request_uri' => $_SERVER['REQUEST_URI'] ?? 'Unknown'
];

// Log dosyasına yaz
$logEntry = "=== NEW REQUEST ===\n";
$logEntry .= json_encode($requestData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$logEntry .= "\n\n";

file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);

// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=utf-8');

// OPTIONS request için
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Bakiye sorgulama endpoint'i
if (isset($_GET['action']) && $_GET['action'] === 'check_balance') {
    try {
        // GET parametrelerinden veri al
        $userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
        $username = isset($_GET['username']) ? trim($_GET['username']) : '';
        
        // Gerekli alanları kontrol et
        if (!$userId || empty($username)) {
            throw new Exception('Gerekli alan eksik: user_id veya username');
        }
        
        // Veritabanından kullanıcı bakiyesini sorgula
        // admin tablosunda username ve bakiye alanları var
        $stmt = $pdo->prepare("
            SELECT bakiye 
            FROM admin 
            WHERE username = ?
        ");
        $stmt->execute([$username]);
        $userData = $stmt->fetch();
        
        if (!$userData) {
            throw new Exception('Kullanıcı bulunamadı: ' . $username);
        }
        
        $userBalance = floatval($userData['bakiye']);
        
        $response = [
            'success' => true,
            'data' => [
                'user_id' => $userId,
                'username' => $username,
                'balance' => $userBalance,
                'currency' => 'TRY'
            ],
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'error' => $e->getMessage(),
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    // Yanıtı logla ve gönder
    $responseLog = "=== BALANCE CHECK RESPONSE ===\n";
    $responseLog .= json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $responseLog .= "\n\n";
    file_put_contents($logFile, $responseLog, FILE_APPEND | LOCK_EX);
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}

// Sadece POST isteklerini kabul et
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response = [
        'success' => false,
        'error' => 'Sadece POST istekleri kabul edilir',
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    // Yanıtı logla
    $responseLog = "=== RESPONSE ===\n";
    $responseLog .= json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $responseLog .= "\n\n";
    file_put_contents($logFile, $responseLog, FILE_APPEND | LOCK_EX);
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    // JSON input'u al
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    // JSON decode hatası kontrol et
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Geçersiz JSON formatı: ' . json_last_error_msg());
    }
    
    // Gerekli alanları kontrol et
    $requiredFields = ['user_id', 'username', 'coupon_amount', 'api_agent_code'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            throw new Exception("Gerekli alan eksik: {$field}");
        }
    }
    
    // Veri doğrulama
    $userId = intval($data['user_id']);
    $username = trim($data['username']);
    $couponAmount = floatval($data['coupon_amount']);
    $apiAgentCode = trim($data['api_agent_code']);
    
    // Ek bilgiler (opsiyonel)
    $couponId = $data['coupon_id'] ?? null;
    $selections = $data['selections'] ?? [];
    $totalOdds = $data['total_odds'] ?? null;
    $potentialWin = $data['potential_win'] ?? null;
    
    // Veri doğrulama
    if ($userId <= 0) {
        throw new Exception('Geçersiz kullanıcı ID');
    }
    
    if ($couponAmount <= 0) {
        throw new Exception('Geçersiz kupon miktarı');
    }
    
    if (empty($username)) {
        throw new Exception('Geçersiz kullanıcı adı');
    }
    
         // Kupon onaylandığında bakiyeden düş
     // Önce mevcut bakiyeyi kontrol et
     $stmt = $pdo->prepare("
         SELECT bakiye 
         FROM admin 
         WHERE username = ?
     ");
     $stmt->execute([$username]);
     $currentBalance = $stmt->fetch();
     
     if (!$currentBalance) {
         throw new Exception('Kullanıcı bulunamadı');
     }
     
     $currentBalanceAmount = floatval($currentBalance['bakiye']);
     
     if ($currentBalanceAmount < $couponAmount) {
         throw new Exception('Yetersiz bakiye. Mevcut: ' . $currentBalanceAmount . ' TL, Gerekli: ' . $couponAmount . ' TL');
     }
     
           // Bakiyeyi güncelle
      $newBalance = $currentBalanceAmount - $couponAmount;
      $updateStmt = $pdo->prepare("
          UPDATE admin 
          SET bakiye = ? 
          WHERE username = ?
      ");
      $updateStmt->execute([$newBalance, $username]);
      
             // Kupon detaylarını kaydet
       $transactionId = 'TXN_' . ($couponId ?? time()) . '_' . time();
       
                       // Kupon detaylarını kaydet
        $insertKuponDetayStmt = $pdo->prepare("
            INSERT INTO kupondetayi (
                userid, username, toplamoran, potansiyelkazanc, 
                kuponmiktari, karsilasma, secenek, baslangic
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        // Karşılaşma bilgisini al (ilk seçimden)
        $karsilasma = 'Bilinmeyen Maç';
        $secenek = 'Bilinmeyen Seçim';
        if (!empty($selections) && is_array($selections) && isset($selections[0])) {
            $karsilasma = $selections[0]['match'] ?? 'Bilinmeyen Maç';
            $secenek = $selections[0]['selection'] ?? 'Bilinmeyen Seçim';
            
            // Seçenek bilgisini düzelt
            if (strpos($secenek, 'Berabere') !== false) {
                $secenek = 'Kazanan Berabere';
            }
        }
        
        // Başlangıç tarihini al
        $baslangic = date('Y-m-d H:i:s');
        
        $insertKuponDetayStmt->execute([
            $userId,
            $username,
            $totalOdds,
            $potentialWin,
            $couponAmount,
            $karsilasma,
            $secenek,
            $baslangic
        ]);
        
        // Kupon detay ID'sini al
        $kuponDetayId = $pdo->lastInsertId();
     
     // Başarılı yanıt
     $response = [
         'success' => true,
         'message' => 'Kupon onaylandı ve bakiye güncellendi',
         'data' => [
             'user_id' => $userId,
             'username' => $username,
             'coupon_amount' => $couponAmount,
             'api_agent_code' => $apiAgentCode,
             'coupon_id' => $couponId,
             'total_odds' => $totalOdds,
             'potential_win' => $potentialWin,
             'selections_count' => count($selections),
             'previous_balance' => $currentBalanceAmount,
             'new_balance' => $newBalance,
                           'deducted_amount' => $couponAmount,
              'transaction_id' => $transactionId,
              'kupon_detay_id' => $kuponDetayId,
              'karsilasma' => $karsilasma,
              'secenek' => $secenek,
             'timestamp' => date('Y-m-d H:i:s')
         ],
         'timestamp' => date('Y-m-d H:i:s')
     ];
    
                    // Başarılı işlemi logla
                $successLog = "=== KUPON OYNANDI - BAKİYE DÜŞÜRÜLDİ ===\n";
                $successLog .= "Kullanıcı ID: {$userId}\n";
                $successLog .= "Kullanıcı Adı: {$username}\n";
                $successLog .= "Kupon Miktarı: {$couponAmount} TL\n";
                $successLog .= "API Agent Kodu: {$apiAgentCode}\n";
                $successLog .= "Kupon ID: " . ($couponId ?? 'N/A') . "\n";
                $successLog .= "Toplam Oran: " . ($totalOdds ?? 'N/A') . "\n";
                $successLog .= "Potansiyel Kazanç: " . ($potentialWin ?? 'N/A') . " TL\n";
                $successLog .= "Seçim Sayısı: " . count($selections) . "\n";
                $successLog .= "Önceki Bakiye: {$currentBalanceAmount} TL\n";
                $successLog .= "Yeni Bakiye: {$newBalance} TL\n";
                                                  $successLog .= "Düşürülen Miktar: {$couponAmount} TL\n";
                 $successLog .= "İşlem ID: {$transactionId}\n";
                 $successLog .= "Kupon Detay ID: {$kuponDetayId}\n";
                 $successLog .= "Kupon Detayları Kaydedildi: BAŞARILI\n";
                 $successLog .= "Karşılaşma: {$karsilasma}\n";
                 $successLog .= "Seçenek: {$secenek}\n";
                 
                 // Kupon detaylarını logla
                if (!empty($selections)) {
                    $successLog .= "KUPON DETAYLARI:\n";
                    foreach ($selections as $index => $selection) {
                        $successLog .= "  Seçim " . ($index + 1) . ":\n";
                        $successLog .= "    Maç: " . ($selection['match'] ?? 'N/A') . "\n";
                        $successLog .= "    Pazar: " . ($selection['market'] ?? 'N/A') . "\n";
                        $successLog .= "    Seçim: " . ($selection['selection'] ?? 'N/A') . "\n";
                        $successLog .= "    Oran: " . ($selection['odds'] ?? 'N/A') . "\n";
                        $successLog .= "    Bahis Tipi: " . ($selection['bet_type'] ?? 'N/A') . "\n";
                        if (isset($selection['eventId'])) {
                            $successLog .= "    Event ID: " . $selection['eventId'] . "\n";
                        }
                    }
                } else {
                    $successLog .= "KUPON DETAYLARI: Seçim bilgisi bulunamadı\n";
                }
                
                $successLog .= "Timestamp: " . date('Y-m-d H:i:s') . "\n\n";
                file_put_contents($logFile, $successLog, FILE_APPEND | LOCK_EX);
    
} catch (Exception $e) {
    // Hata durumunda
    $response = [
        'success' => false,
        'error' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    // Hatayı logla
    $errorLog = "=== ERROR ===\n";
    $errorLog .= "Error: " . $e->getMessage() . "\n";
    $errorLog .= "Timestamp: " . date('Y-m-d H:i:s') . "\n";
    $errorLog .= "Kupon Detayları Kaydedildi: BAŞARISIZ (Hata nedeniyle)\n\n";
    file_put_contents($logFile, $errorLog, FILE_APPEND | LOCK_EX);
}

// Yanıtı logla
$responseLog = "=== FINAL RESPONSE ===\n";
$responseLog .= json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$responseLog .= "\n\n";
file_put_contents($logFile, $responseLog, FILE_APPEND | LOCK_EX);

// Yanıtı gönder
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
