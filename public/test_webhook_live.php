<?php
if (($_GET['key'] ?? '') !== 'AUTO_TRANSFER_SECRET_12345') {
    http_response_code(403);
    die('Unauthorized');
}

header('Content-Type: text/plain; charset=utf-8');

echo "=== ENV KONTROL ===\n\n";

// .env'den MAIN_WEBHOOK_URL oku
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $envLines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envLines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, 'MAIN_WEBHOOK_URL') !== false ||
            strpos($line, 'MAIN_ORIGIN_IP') !== false ||
            strpos($line, 'APP_URL') !== false) {
            echo $line . "\n";
        }
    }
} else {
    echo ".env dosyası bulunamadı!\n";
}

echo "\n=== WEBHOOK TEST ===\n\n";

$webhookUrl = 'http://localhost';
if (file_exists($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (strpos(trim($line), 'MAIN_WEBHOOK_URL') === 0) {
            $webhookUrl = trim(explode('=', $line, 2)[1], " \"'\t\n\r\0\x0B");
            break;
        }
    }
}

echo "Webhook URL: {$webhookUrl}\n\n";

// Test POST gönder
$parsedUrl = parse_url($webhookUrl);
$webhookHost = $parsedUrl['host'] ?? '';
$webhookPort = ($parsedUrl['scheme'] ?? 'https') === 'https' ? 443 : 80;

// Origin IP
$originIp = '45.90.99.60';
if (file_exists($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $envLine) {
        if (strpos(trim($envLine), 'MAIN_ORIGIN_IP') === 0) {
            $originIp = trim(explode('=', $envLine, 2)[1], " \"'\t\n\r\0\x0B");
            break;
        }
    }
}

echo "Origin IP: {$originIp}\n";
echo "Host: {$webhookHost}\n";
echo "Port: {$webhookPort}\n\n";

$testData = [
    'source_user_id' => '99999',
    'amount' => 0.01,
    'txn' => 'test-ping-' . time(),
    'transaction_id' => 0
];

$ch = curl_init($webhookUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Gateway-Secret: AUTO_TRANSFER_SECRET_12345',
    'Host: ' . $webhookHost
]);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
curl_setopt($ch, CURLOPT_RESOLVE, [
    "{$webhookHost}:{$webhookPort}:{$originIp}"
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$curlError = curl_error($ch);
$curlInfo = curl_getinfo($ch);
curl_close($ch);

$responseHeaders = substr($response, 0, $headerSize);
$responseBody = substr($response, $headerSize);

echo "=== SONUÇ ===\n";
echo "HTTP Code: {$httpCode}\n";
echo "cURL Error: {$curlError}\n";
echo "Effective URL: {$curlInfo['url']}\n";
echo "Primary IP: {$curlInfo['primary_ip']}\n";
echo "Connect Time: {$curlInfo['connect_time']}s\n";
echo "Total Time: {$curlInfo['total_time']}s\n\n";
echo "Response Headers:\n{$responseHeaders}\n";
echo "Response Body:\n" . substr($responseBody, 0, 2000) . "\n";
