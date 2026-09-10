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
// smsonaylatr DB bilgileri
$smsonayDbHost = '127.0.0.1';
$smsonayDbName = $smsonayDbUser = $smsonayDbPass = '';

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
        if ($key === 'SMSONAY_DB_HOST') $smsonayDbHost = $val;
        if ($key === 'SMSONAY_DB_DATABASE') $smsonayDbName = $val;
        if ($key === 'SMSONAY_DB_USERNAME') $smsonayDbUser = $val;
        if ($key === 'SMSONAY_DB_PASSWORD') $smsonayDbPass = $val;
    }
}

try {
    $conn = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Smsbridge DB hatası: " . $e->getMessage());
}

// smsonaylatr DB bağlantısı
$smsonayConn = null;
if (!empty($smsonayDbName) && !empty($smsonayDbUser)) {
    try {
        $smsonayConn = new PDO("mysql:host={$smsonayDbHost};dbname={$smsonayDbName};charset=utf8mb4", $smsonayDbUser, $smsonayDbPass);
        $smsonayConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "✅ smsonaylatr DB bağlantısı başarılı!\n\n";
    } catch (PDOException $e) {
        echo "❌ smsonaylatr DB bağlantı hatası: " . $e->getMessage() . "\n\n";
    }
}

if (!$smsonayConn) {
    echo "⚠️ smsonaylatr DB bilgileri .env'de bulunamadı!\n";
    echo "Lütfen smsbridge .env dosyasına şu satırları ekleyin:\n\n";
    echo "SMSONAY_DB_HOST=127.0.0.1\n";
    echo "SMSONAY_DB_DATABASE=smsonay_db_adi\n";
    echo "SMSONAY_DB_USERNAME=smsonay_kullanici\n";
    echo "SMSONAY_DB_PASSWORD=smsonay_sifre\n\n";
    
    // Plesk'ten DB listesini göstermeye çalış
    echo "=== MEVCUT VERITABANLARI ===\n";
    try {
        $dbs = $conn->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($dbs as $db) {
            echo "  - {$db}\n";
        }
    } catch (Exception $e) {
        echo "DB listesi alınamadı\n";
    }
    die("\n");
}

echo "=== DOĞRUDAN DB ÜZERİNDEN ONAY ===\n";
echo "Tarih: " . date('Y-m-d H:i:s') . "\n\n";

// Smsbridge'de onaylanmış (durum=1) gateway işlemleri
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
$skipCount = 0;
$failCount = 0;

foreach ($deposits as $deposit) {
    preg_match('/gateway_source_user_id:(\d+)\|ref:(.+)/', $deposit['note'], $matches);
    if (count($matches) < 3) continue;
    
    $sourceUserId = $matches[1];
    $txn = $matches[2];
    $amount = (float) $deposit['miktar'];
    
    echo "--- İşlem: source_user_id={$sourceUserId}, amount={$amount}, txn={$txn} ---\n";
    
    // smsonaylatr'da bekleyen ödemeyi bul
    $findStmt = $smsonayConn->prepare("
        SELECT p.id, p.payment_id, p.user_id, p.amount, p.status, p.phone, u.balance, u.email 
        FROM payments p 
        JOIN users u ON u.id = p.user_id
        WHERE p.user_id = :user_id 
        AND p.type = 'EXTRA_HAVALE_AUTO' 
        AND p.status = 0
        AND p.amount = :amount
        ORDER BY p.id DESC 
        LIMIT 1
    ");
    $findStmt->execute([':user_id' => $sourceUserId, ':amount' => $amount]);
    $payment = $findStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$payment) {
        // Tutar farklı olabilir, sadece user_id ile ara
        $findStmt2 = $smsonayConn->prepare("
            SELECT p.id, p.payment_id, p.user_id, p.amount, p.status, p.phone, u.balance, u.email 
            FROM payments p 
            JOIN users u ON u.id = p.user_id
            WHERE p.user_id = :user_id 
            AND p.type = 'EXTRA_HAVALE_AUTO' 
            AND p.status = 0
            ORDER BY p.id DESC 
            LIMIT 1
        ");
        $findStmt2->execute([':user_id' => $sourceUserId]);
        $payment = $findStmt2->fetch(PDO::FETCH_ASSOC);
    }
    
    if (!$payment) {
        echo "  ⏩ Bekleyen ödeme bulunamadı (zaten onaylanmış olabilir)\n\n";
        $skipCount++;
        continue;
    }
    
    echo "  Bulunan ödeme: payment_id={$payment['payment_id']}, amount={$payment['amount']}, user_balance={$payment['balance']}\n";
    
    // Bakiye güncelle
    $beforeBalance = (float) $payment['balance'];
    $newBalance = $beforeBalance + $amount;
    
    try {
        $smsonayConn->beginTransaction();
        
        // Kullanıcı bakiyesini güncelle
        $smsonayConn->prepare("UPDATE users SET balance = :balance WHERE id = :id")
            ->execute([':balance' => $newBalance, ':id' => $payment['user_id']]);
        
        // Ödeme durumunu güncelle
        $smsonayConn->prepare("UPDATE payments SET status = 1, amount = :amount WHERE id = :id")
            ->execute([':amount' => $amount, ':id' => $payment['id']]);
        
        // Bakiye geçmişi ekle
        $smsonayConn->prepare("
            INSERT INTO balance_histories (user_id, to_user_id, amount, new_amount, type, description, created_at, updated_at)
            VALUES (:user_id, :to_user_id, :amount, :new_amount, 'credit', :desc, NOW(), NOW())
        ")->execute([
            ':user_id' => $payment['user_id'],
            ':to_user_id' => $payment['user_id'],
            ':amount' => $beforeBalance,
            ':new_amount' => $newBalance,
            ':desc' => 'Oto Havale (Extra) Yatırımı Otomatik Onaylandı (Miktar: ' . $amount . ' ₺) [DB Direct]'
        ]);
        
        $smsonayConn->commit();
        
        echo "  ✅ ONAYLANDI! Bakiye: {$beforeBalance} → {$newBalance}\n\n";
        $successCount++;
    } catch (Exception $e) {
        $smsonayConn->rollBack();
        echo "  ❌ HATA: " . $e->getMessage() . "\n\n";
        $failCount++;
    }
}

echo "=============================\n";
echo "Toplam: " . count($deposits) . " | Onaylandı: {$successCount} | Atlandı: {$skipCount} | Hata: {$failCount}\n";
