<?php
$host = 'localhost';
$dbname = 'robinbet_mysql';
$user = 'robinbet_mysql';
$pass = 'Robinbet3434';

/**
 * Loglama fonksiyonu: Header bilgisiyle birlikte mesajı log.txt'ye ekler.
 */
function logMessage($header, $message) {
    $logFile = __DIR__ . "/log.txt";
    $time = date("Y-m-d H:i:s");
    $logEntry  = "--------------------------\n";
    $logEntry .= "$time - $header:\n";
    $logEntry .= $message . "\n";
    $logEntry .= "--------------------------\n\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

/**
 * Yanıt gönderme fonksiyonu: API yanıtını JSON olarak döner ve her durumda log tutar.
 */
function sendResponse($status, $message, $httpCode, $additionalData = array()) {
    $response = array('status' => $status, 'message' => $message);
    if (!empty($additionalData)) {
        $response = array_merge($response, $additionalData);
    }
    // Log outgoing response
    logMessage('Giden', json_encode($response));
    header('Content-Type: application/json');
    http_response_code($httpCode);
    echo json_encode($response);
    exit;
}

// Gelen raw JSON veriyi oku ve logla
$rawData = file_get_contents('php://input');
logMessage('Gelen', $rawData);

// Decode
$data = json_decode($rawData, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    sendResponse('error', 'Invalid input data', 400);
}

// Veritabanı bağlantısı kurma
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    sendResponse('error', 'Database connection failed', 500);
}

/**
 * Veritabanından kullanıcı verisini alma
 */
function getUserFromDatabase($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Veritabanında kullanıcının bakiye bilgisini güncelleme
 */
function updateUserBalance($user_id, $new_balance) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE admin SET bakiye = :balance WHERE id = :user_id");
    $stmt->execute(['balance' => $new_balance, 'user_id' => $user_id]);
}

/**
 * Veritabanında kullanıcının cevrim bilgisini güncelleme
 */
function updateUserCevrim($user_id, $new_cevrim) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE admin SET cevrim = :cevrim WHERE id = :user_id");
    $stmt->execute(['cevrim' => $new_cevrim, 'user_id' => $user_id]);
}

/**
 * Oyun adını getir
 */
function getGameName($game_id) {
    $gameNames = [
        '23002' => 'Gates of Olympus',
        '23724' => 'Gates of Olympus Dice',
        '23003' => 'Sweet Bonanza',
        '23004' => 'Book of Dead',
        '23005' => 'Wolf Gold',
        '23006' => 'The Dog House',
        '23007' => 'Fruit Party',
        '23008' => 'Wild West Gold',
        '23009' => 'Aztec Gems',
        '23010' => 'Aviator',
        // Daha fazla oyun eklenebilir
    ];
    
    return isset($gameNames[$game_id]) ? $gameNames[$game_id] : 'Unknown Game (' . $game_id . ')';
}

/**
 * Kazanma yöntemini belirle
 */
function getWinMethod($data) {
    $winMethod = 'Normal Win';
    
    // Free spins kazanma
    if (isset($data['free_spins']) && $data['free_spins'] > 0) {
        $winMethod = 'Free Spins Win';
    }
    
    // Bonus game kazanma
    if (isset($data['bonus_game']) && $data['bonus_game']) {
        $winMethod = 'Bonus Game Win';
    }
    
    // Jackpot kazanma
    if (isset($data['jackpot']) && $data['jackpot']) {
        $winMethod = 'Jackpot Win';
    }
    
    // Mega win (büyük kazanç)
    if (isset($data['win']) && $data['win'] > 1000) {
        $winMethod = 'Mega Win';
    }
    
    return $winMethod;
}

/**
 * Detaylı işlem açıklaması oluştur
 */
function createTransactionDescription($data, $type) {
    $description = '';
    
    if ($type === 'bet') {
        $gameName = getGameName($data['game'] ?? '');
        $betAmount = $data['bet'] ?? 0;
        $description = "Bet placed on {$gameName} - Amount: {$betAmount} TL";
        
        // Spin bilgisi varsa ekle
        if (isset($data['spin_number'])) {
            $description .= " (Spin #{$data['spin_number']})";
        }
        
        // Free spin bilgisi varsa ekle
        if (isset($data['free_spins']) && $data['free_spins'] > 0) {
            $description .= " - Free Spins: {$data['free_spins']}";
        }
        
    } elseif ($type === 'win') {
        $gameName = getGameName($data['game'] ?? '');
        $winAmount = $data['win'] ?? 0;
        $winMethod = getWinMethod($data);
        $description = "Win on {$gameName} - Amount: {$winAmount} TL - Method: {$winMethod}";
        
        // Spin bilgisi varsa ekle
        if (isset($data['spin_number'])) {
            $description .= " (Spin #{$data['spin_number']})";
        }
        
        // Multiplier bilgisi varsa ekle
        if (isset($data['multiplier']) && $data['multiplier'] > 1) {
            $description .= " - Multiplier: {$data['multiplier']}x";
        }
        
        // Line bilgisi varsa ekle
        if (isset($data['win_lines'])) {
            $description .= " - Win Lines: {$data['win_lines']}";
        }
        
    } elseif ($type === 'refund') {
        $gameName = getGameName($data['game'] ?? '');
        $refundAmount = $data['refund'] ?? 0;
        $description = "Refund on {$gameName} - Amount: {$refundAmount} TL";
    }
    
    return $description;
}

/**
 * Veritabanına detaylı işlem kaydetme (Türkiye saatine göre)
 */
function logTransaction($user_id, $type, $amount, $gameid = null, $transactionid = null, $session_id = null, $description = null, $additional_data = null) {
    global $pdo;
    date_default_timezone_set('Europe/Istanbul');
    $current_time = date("Y-m-d H:i:s");
    
    // Ek verileri JSON olarak sakla
    $additional_json = $additional_data ? json_encode($additional_data) : null;
    
    try {
        // Tek tabloda tüm bilgileri kaydet
        $gameName = getGameName($gameid);
        $winMethod = ($type === 'win') ? getWinMethod($additional_data ?? []) : null;
        $multiplier = isset($additional_data['multiplier']) ? $additional_data['multiplier'] : null;
        $spinNumber = isset($additional_data['spin_number']) ? $additional_data['spin_number'] : null;
        $freeSpins = isset($additional_data['free_spins']) ? $additional_data['free_spins'] : null;
        
        $stmt = $pdo->prepare("
            INSERT INTO transactions 
            (user_id, type, amount, created_at, gameid, game_name, transactionid, session_id, description, additional_data, win_method, multiplier, spin_number, free_spins) 
            VALUES 
            (:user_id, :type, :amount, :created_at, :gameid, :game_name, :transactionid, :session_id, :description, :additional_data, :win_method, :multiplier, :spin_number, :free_spins)
        ");
        $stmt->execute([
            'user_id'        => $user_id,
            'type'           => $type,
            'amount'         => $amount,
            'created_at'     => $current_time,
            'gameid'         => $gameid,
            'game_name'      => $gameName,
            'transactionid'  => $transactionid,
            'session_id'     => $session_id,
            'description'    => $description,
            'additional_data' => $additional_json,
            'win_method'     => $winMethod,
            'multiplier'     => $multiplier,
            'spin_number'    => $spinNumber,
            'free_spins'     => $freeSpins
        ]);
        
    } catch (PDOException $e) {
        error_log("logTransaction detaylı sorgu hatası: " . $e->getMessage());
        
        // Hata durumunda basit versiyona düş
        try {
            $stmt = $pdo->prepare("
                INSERT INTO transactions 
                (user_id, type, amount, created_at, gameid, transactionid, session_id) 
                VALUES 
                (:user_id, :type, :amount, :created_at, :gameid, :transactionid, :session_id)
            ");
            $stmt->execute([
                'user_id'       => $user_id,
                'type'          => $type,
                'amount'        => $amount,
                'created_at'    => $current_time,
                'gameid'        => $gameid,
                'transactionid' => $transactionid,
                'session_id'    => $session_id
            ]);
        } catch (PDOException $e2) {
            error_log("logTransaction basit sorgu da hata: " . $e2->getMessage());
            // En basit versiyon
            $stmt = $pdo->prepare("
                INSERT INTO transactions 
                (user_id, type, amount, created_at) 
                VALUES 
                (:user_id, :type, :amount, :created_at)
            ");
            $stmt->execute([
                'user_id'    => $user_id,
                'type'       => $type,
                'amount'     => $amount,
                'created_at' => $current_time
            ]);
        }
    }
}

// İstek işleme
if (!isset($data['method'])) {
    sendResponse('error', 'Method not specified', 400);
}

$user_id = isset($data['user_id']) ? (int)$data['user_id'] : null;
if (!$user_id) {
    sendResponse('error', 'User ID is required', 400);
}

switch ($data['method']) {
    case 'account_details':
        $user = getUserFromDatabase($user_id);
        if ($user) {
            sendResponse('success', 'User details retrieved successfully', 200, [
                'user_id'      => $user['id'],
                'email'        => $user['email'],
                'name_jogador' => $user['username']
            ]);
        } else {
            sendResponse('error', 'User not found', 404, ['provided_user_id' => $user_id]);
        }
        break;

    case 'user_balance':
        $user = getUserFromDatabase($user_id);
        if ($user) {
            sendResponse('success', 'User balance retrieved successfully', 200, [
                'status'  => 1,
                'balance' => number_format((float)$user['bakiye'], 2, '.', '')
            ]);
        } else {
            sendResponse('error', 'User not found', 404, ['provided_user_id' => $user_id]);
        }
        break;

    case 'transaction_bet':
        $bet_amount = isset($data['bet']) ? (float)$data['bet'] : null;
        if ($bet_amount === null) {
            sendResponse('error', 'Bet amount is required', 400);
        }
        $gameid        = $data['game'] ?? null;
        $transactionid = $data['transaction_id'] ?? null;
        $session_id    = $data['session_id'] ?? null;

        $user = getUserFromDatabase($user_id);
        if (!$user) {
            sendResponse('error', 'User not found', 404, ['provided_user_id' => $user_id]);
        }
        $new_balance = $user['bakiye'] - $bet_amount;
        if ($new_balance < 0) {
            sendResponse('error', 'Insufficient balance', 400, [
                'provided_user_id'    => $user_id,
                'provided_bet_amount' => $bet_amount
            ]);
        }
        
        updateUserBalance($user_id, $new_balance);
        if (isset($user['cevrim'])) {
            $new_cevrim = max(0, $user['cevrim'] - $bet_amount);
            updateUserCevrim($user_id, $new_cevrim);
        }
        
        // Detaylı açıklama oluştur
        $description = createTransactionDescription($data, 'bet');
        
        // Ek verileri hazırla
        $additionalData = [
            'spin_number' => $data['spin_number'] ?? null,
            'free_spins' => $data['free_spins'] ?? null,
            'bet_per_line' => $data['bet_per_line'] ?? null,
            'lines' => $data['lines'] ?? null,
            'total_bet' => $bet_amount
        ];
        
        logTransaction($user_id, 'bet', $bet_amount, $gameid, $transactionid, $session_id, $description, $additionalData);
        
        sendResponse('success', 'Bet transaction successful', 200, [
            'status'  => 1,
            'balance' => number_format($new_balance, 2, '.', '')
        ]);
        break;

    case 'transaction_win':
        $win_amount    = isset($data['win']) ? (float)$data['win'] : null;
        if ($win_amount === null) {
            sendResponse('error', 'Win amount is missing', 400);
        }
        $gameid        = $data['game'] ?? null;
        $transactionid = $data['transaction_id'] ?? null;
        $session_id    = $data['session_id'] ?? null;

        $user = getUserFromDatabase($user_id);
        if (!$user) {
            sendResponse('error', 'User not found', 404, ['provided_user_id' => $user_id]);
        }
        $new_balance = $user['bakiye'] + $win_amount;
        updateUserBalance($user_id, $new_balance);
        
        // Detaylı açıklama oluştur
        $description = createTransactionDescription($data, 'win');
        
        // Ek verileri hazırla
        $additionalData = [
            'spin_number' => $data['spin_number'] ?? null,
            'free_spins' => $data['free_spins'] ?? null,
            'multiplier' => $data['multiplier'] ?? null,
            'win_lines' => $data['win_lines'] ?? null,
            'bonus_game' => $data['bonus_game'] ?? false,
            'jackpot' => $data['jackpot'] ?? false,
            'win_method' => getWinMethod($data)
        ];
        
        logTransaction($user_id, 'win', $win_amount, $gameid, $transactionid, $session_id, $description, $additionalData);
        
        sendResponse('success', 'Win transaction successful', 200, [
            'status'  => 1,
            'balance' => number_format($new_balance, 2, '.', '')
        ]);
        break;

    case 'refund':
        $refund_amount = isset($data['refund']) ? (float)$data['refund'] : null;
        if ($refund_amount === null) {
            sendResponse('error', 'Refund amount is required', 400);
        }
        $user = getUserFromDatabase($user_id);
        if (!$user) {
            sendResponse('error', 'User not found', 404, ['provided_user_id' => $user_id]);
        }
        $new_balance = $user['bakiye'] + $refund_amount;
        updateUserBalance($user_id, $new_balance);
        
        // Detaylı açıklama oluştur
        $description = createTransactionDescription($data, 'refund');
        
        logTransaction($user_id, 'refund', $refund_amount, $data['game'] ?? null, $data['transaction_id'] ?? null, $data['session_id'] ?? null, $description);
        
        sendResponse('success', 'Refund transaction successful', 200, [
            'status'  => 1,
            'balance' => number_format($new_balance, 2, '.', '')
        ]);
        break;

    default:
        sendResponse('error', 'Invalid method', 400);
        break;
}
?>
