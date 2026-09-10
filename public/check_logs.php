<?php
// Güvenlik: Sadece belirli secret ile erişim
if (($_GET['key'] ?? '') !== 'AUTO_TRANSFER_SECRET_12345') {
    http_response_code(403);
    die('Unauthorized');
}

$logFile = __DIR__ . '/../storage/logs/laravel.log';

if (!file_exists($logFile)) {
    die('Log dosyası bulunamadı: ' . $logFile);
}

$filter = $_GET['filter'] ?? 'IBAN Fetch|Gateway API|ExtPayment|AutoApproveExtraHavale';
$lines = (int) ($_GET['lines'] ?? 300);

// Son N satırı oku
$allLines = file($logFile, FILE_IGNORE_NEW_LINES);
$totalLines = count($allLines);
$startLine = max(0, $totalLines - $lines);
$recentLines = array_slice($allLines, $startLine);

header('Content-Type: text/plain; charset=utf-8');
echo "=== SMSBRIDGE LOG VIEWER ===\n";
echo "Toplam satır: {$totalLines} | Son {$lines} satır gösteriliyor\n";
echo "Filtre: {$filter}\n";
echo "=============================\n\n";

$matchCount = 0;
foreach ($recentLines as $line) {
    if (preg_match('/' . $filter . '/i', $line)) {
        echo $line . "\n";
        $matchCount++;
    }
}

echo "\n=============================\n";
echo "Eşleşen satır: {$matchCount}\n";
