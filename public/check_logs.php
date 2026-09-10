<?php
// Güvenlik: Sadece belirli secret ile erişim
if (($_GET['key'] ?? '') !== 'AUTO_TRANSFER_SECRET_12345') {
    http_response_code(403);
    die('Unauthorized');
}

$source = $_GET['source'] ?? 'all';
$filter = $_GET['filter'] ?? 'GATEWAY WEBHOOK|Gateway API|IBAN Fetch|AutoApproveExtraHavale';
$lines = (int) ($_GET['lines'] ?? 500);

header('Content-Type: text/plain; charset=utf-8');

// extra.php logları (1.txt)
if ($source === 'all' || $source === 'extra') {
    $extraLog = __DIR__ . '/1.txt';
    if (file_exists($extraLog)) {
        $allLines = file($extraLog, FILE_IGNORE_NEW_LINES);
        $totalLines = count($allLines);
        $startLine = max(0, $totalLines - $lines);
        $recentLines = array_slice($allLines, $startLine);
        
        echo "=== EXTRA.PHP LOG (1.txt) ===\n";
        echo "Toplam satır: {$totalLines} | Son {$lines} satır\n";
        echo "=============================\n\n";
        
        $matchCount = 0;
        foreach ($recentLines as $line) {
            if (empty($filter) || preg_match('/' . $filter . '/i', $line)) {
                echo $line . "\n";
                $matchCount++;
            }
        }
        echo "\nEşleşen: {$matchCount}\n\n";
    } else {
        echo "=== EXTRA.PHP LOG (1.txt) — DOSYA BULUNAMADI ===\n\n";
    }
}

// Laravel logları
if ($source === 'all' || $source === 'laravel') {
    $laravelLog = __DIR__ . '/../storage/logs/laravel.log';
    if (file_exists($laravelLog)) {
        $allLines = file($laravelLog, FILE_IGNORE_NEW_LINES);
        $totalLines = count($allLines);
        $startLine = max(0, $totalLines - $lines);
        $recentLines = array_slice($allLines, $startLine);
        
        echo "=== LARAVEL LOG ===\n";
        echo "Toplam satır: {$totalLines} | Son {$lines} satır\n";
        echo "=============================\n\n";
        
        $matchCount = 0;
        foreach ($recentLines as $line) {
            if (empty($filter) || preg_match('/' . $filter . '/i', $line)) {
                echo $line . "\n";
                $matchCount++;
            }
        }
        echo "\nEşleşen: {$matchCount}\n";
    } else {
        echo "=== LARAVEL LOG — DOSYA BULUNAMADI ===\n";
    }
}
