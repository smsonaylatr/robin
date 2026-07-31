<?php


header('Content-Type: application/json');

// MySQL Veritabanı Bağlantısı
$db_host = 'localhost';
$db_name = 'robinbet_mysql';
$db_user = 'robinbet_mysql';
$db_pass = 'Robinbet3434';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Veritabanı bağlantı hatası'
    ]);
    exit;
}

// POST verisini al
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$user_id = isset($data['user_id']) ? $data['user_id'] : null;
$username = isset($data['username']) ? $data['username'] : null;
$miktar = isset($data['miktar']) ? floatval($data['miktar']) : 0;
$status = isset($data['status']) ? $data['status'] : '';

// Kullanıcıyı veritabanından bul
$stmt = $pdo->prepare("SELECT id, username, bakiye FROM admin WHERE id = ? OR username = ?");
$stmt->execute([$user_id, $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'error' => 'Kullanıcı bulunamadı'
    ]);
    exit;
}

// Status: bet veya "Bahis alındı" (Kupon oynatıldı, bakiye düş)
if ($status === 'bet' || $status === 'Bahis alındı') {
    // Bakiye yeterli mi?
    if ($user['bakiye'] < $miktar) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Bakiyeniz yetersiz',
            'bakiye' => floatval($user['bakiye']),
            'istenen' => floatval($miktar)
        ]);
        exit;
    }
    
    // Bakiyeyi düş (MySQL UPDATE)
    $stmt = $pdo->prepare("UPDATE admin SET bakiye = bakiye - ? WHERE id = ?");
    $stmt->execute([$miktar, $user['id']]);
    
    // Yeni bakiyeyi al
    $stmt = $pdo->prepare("SELECT bakiye FROM admin WHERE id = ?");
    $stmt->execute([$user['id']]);
    $yeniBakiye = $stmt->fetchColumn();
    
    echo json_encode([
        'success' => true,
        'message' => 'Bakiye düşüldü',
        'eski_bakiye' => floatval($user['bakiye']),
        'dusurilen' => floatval($miktar),
        'yeni_bakiye' => floatval($yeniBakiye)
    ]);
    exit;
}

// Status: win (Kupon kazandı, bakiye ekle)
if ($status === 'win') {
    // amount parametresini al
    $amount = isset($data['amount']) ? floatval($data['amount']) : $miktar;
    
    if ($amount <= 0) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Kazanç miktarı geçersiz',
            'amount' => $amount
        ]);
        exit;
    }
    
    // Bakiyeyi artır (MySQL UPDATE)
    $stmt = $pdo->prepare("UPDATE admin SET bakiye = bakiye + ? WHERE id = ?");
    $stmt->execute([$amount, $user['id']]);
    
    // Yeni bakiyeyi al
    $stmt = $pdo->prepare("SELECT bakiye FROM admin WHERE id = ?");
    $stmt->execute([$user['id']]);
    $yeniBakiye = $stmt->fetchColumn();
    
    echo json_encode([
        'success' => true,
        'message' => 'Kupon kazandı, bakiye eklendi',
        'eski_bakiye' => floatval($user['bakiye']),
        'eklenen' => floatval($amount),
        'yeni_bakiye' => floatval($yeniBakiye)
    ]);
    exit;
}

// Status boşsa veya farklıysa sadece bakiye döndür
echo json_encode([
    'success' => true,
    'user_id' => $user['id'],
    'username' => $user['username'],
    'bakiye' => floatval($user['bakiye'])
]);
