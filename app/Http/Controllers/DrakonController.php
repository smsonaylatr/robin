<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DrakonController extends Controller
{
    // Veritral API credentials
    private $veritralApiUri = 'https://api.veritral.com/api/v2';
    private $veritralApiKey = 'api_qda2802r2_mhqfmvg9';
    private $veritralSecretKey = 'sec_a9jnc01tp1e_mhqfmvg9';

    /**
     * Launch game - Returns game URL for new tab opening (Veritral API)
     */
    public function gameLaunch($gameId)
    {
        $user = auth('admin')->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        try {
            // Veritral API için JSON body hazırla
            $requestData = [
                'api_key' => $this->veritralApiKey,
                'secret_key' => $this->veritralSecretKey,
                'username' => $user->username,
                'player_id' => (string)$user->id,
                'game_id' => (string)$gameId,
                'currency' => 'TRY',
                'language' => 'tr'
            ];

            Log::info('Veritral API game launch request', [
                'game_id' => $gameId,
                'user_id' => $user->id,
                'username' => $user->username,
                'endpoint' => $this->veritralApiUri . '/launch'
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->veritralApiUri . '/launch', $requestData);

            Log::info('Veritral API game launch response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Veritral API yanıt formatına göre kontrol et
                // API yanıtı: {"status":"success","data":{"launch_url":"..."}}
                $launchUrl = null;
                
                if (isset($data['data']['launch_url']) && !empty($data['data']['launch_url'])) {
                    $launchUrl = $data['data']['launch_url'];
                } elseif (isset($data['game_url']) && !empty($data['game_url'])) {
                    $launchUrl = $data['game_url'];
                } elseif (isset($data['url']) && !empty($data['url'])) {
                    $launchUrl = $data['url'];
                } elseif (isset($data['data']['url']) && !empty($data['data']['url'])) {
                    $launchUrl = $data['data']['url'];
                }
                
                if ($launchUrl) {
                    return response()->json([
                        'success' => true,
                        'game_url' => $launchUrl
                    ]);
                } else {
                    Log::error('Veritral API: Game URL not found in response', $data);
                    return response()->json(['error' => 'Oyun URL\'si alınamadı'], 500);
                }
            }

            Log::error('Veritral API game launch failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return response()->json(['error' => 'Oyun başlatılamadı'], 500);

        } catch (\Exception $e) {
            Log::error('Veritral API game launch error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json(['error' => 'Oyun başlatma hatası'], 500);
        }
    }

    /**
     * Direct game launch - Redirects to game URL (Veritral API)
     */
    public function directGameLaunch($gameId)
    {
        // Debug: Check if user is authenticated
        if (!auth('admin')->check()) {
            return response('Kullanıcı giriş yapmamış. Lütfen önce giriş yapın.', 401);
        }

        $user = auth('admin')->user();

        try {
            // Veritral API için JSON body hazırla
            $requestData = [
                'api_key' => $this->veritralApiKey,
                'secret_key' => $this->veritralSecretKey,
                'username' => $user->username,
                'player_id' => (string)$user->id,
                'game_id' => (string)$gameId,
                'currency' => 'TRY',
                'language' => 'tr'
            ];
            
            Log::info('Veritral API direct game launch request', [
                'game_id' => $gameId,
                'user_id' => $user->id,
                'username' => $user->username,
                'endpoint' => $this->veritralApiUri . '/launch',
                'params' => $requestData
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(30)
                ->post($this->veritralApiUri . '/launch', $requestData);

            // Debug: Log the response
            $rawBody = $response->toPsrResponse()->getBody()->getContents();
            Log::info('Veritral API response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'body_length' => strlen($response->body()),
                'raw_body_length' => strlen($rawBody),
                'headers' => $response->headers(),
                'raw_response' => $rawBody
            ]);

            if ($response->successful()) {
                // Raw body'den parse et
                $data = json_decode($rawBody, true);
                
                if ($data === null) {
                    Log::error('JSON decode failed', [
                        'raw_body' => $rawBody,
                        'json_error' => json_last_error_msg()
                    ]);
                    return response('API yanıtı parse edilemedi: ' . json_last_error_msg(), 500);
                }
                
                Log::info('Veritral API response data', $data);
                
                // Veritral API yanıt formatına göre game_url'yi kontrol et
                // API yanıtı: {"status":"success","data":{"launch_url":"..."}}
                $gameUrl = null;
                
                if (isset($data['data']['launch_url']) && !empty($data['data']['launch_url'])) {
                    $gameUrl = $data['data']['launch_url'];
                } elseif (isset($data['game_url']) && !empty($data['game_url'])) {
                    $gameUrl = $data['game_url'];
                } elseif (isset($data['url']) && !empty($data['url'])) {
                    $gameUrl = $data['url'];
                } elseif (isset($data['data']['url']) && !empty($data['data']['url'])) {
                    $gameUrl = $data['data']['url'];
                }
                
                if ($gameUrl) {
                    Log::info('Raw game URL from Veritral API', [
                        'game_url' => $gameUrl,
                        'contains_amp' => strpos($gameUrl, '&amp;') !== false,
                        'contains_ampersand' => strpos($gameUrl, '&') !== false,
                        'url_length' => strlen($gameUrl)
                    ]);
                    
                    // URL'deki HTML encoding'i düzelt - daha agresif decode
                    $decodedUrl = html_entity_decode($gameUrl, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $decodedUrl = str_replace('&amp;', '&', $decodedUrl);
                    $decodedUrl = urldecode($decodedUrl);
                    
                    Log::info('Decoded game URL', [
                        'original' => $gameUrl, 
                        'decoded' => $decodedUrl,
                        'contains_amp' => strpos($decodedUrl, '&amp;') !== false
                    ]);
                    
                    // Eski PHP kodundaki gibi doğrudan yönlendir
                    return redirect($decodedUrl);
                } else {
                    Log::error('Game URL is empty in Veritral API response', $data);
                    return response('Oyun URL\'si alınamadı. API yanıtı: ' . json_encode($data), 500);
                }
            } else {
                $errorBody = $response->body();
                Log::error('Veritral API error response', [
                    'status' => $response->status(),
                    'body' => $errorBody
                ]);
                
                return response('API hatası (HTTP ' . $response->status() . '): ' . $errorBody, 500);
            }

        } catch (\Exception $e) {
            Log::error('Veritral API game launch exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response('Oyun başlatma hatası: ' . $e->getMessage(), 500);
        }
    }


    /**
     * Loglama fonksiyonu: Header bilgisiyle birlikte mesajı log.txt'ye ekler.
     */
    private function logMessage($header, $message) {
        $logFile = storage_path('logs/log.txt');
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
    private function sendResponse($status, $message, $httpCode, $additionalData = array()) {
        // Veritral API formatına göre
        if ($status === 'success') {
            $response = $additionalData; // Başarılı durumda sadece ek verileri döndür
        } else {
            // Hata durumunda status 0 ve mesaj
            $response = array('status' => 0, 'message' => $message);
            if (!empty($additionalData)) {
                $response = array_merge($response, $additionalData);
            }
        }
        
        // Log outgoing response
        $this->logMessage('Giden', json_encode($response));
        
        // Eski PHP sistemindeki gibi header ve exit
        header('Content-Type: application/json');
        http_response_code($httpCode);
        echo json_encode($response);
        exit;
    }

    /**
     * Veritabanına işlem kaydetme (Türkiye saatine göre)
     * gamename alanı zorunlu olduğu için eklenmiştir.
     */
    private function logTransaction($user_id, $type, $amount, $gameid = null, $transactionid = null, $session_id = null, $gamename = null) {
        date_default_timezone_set('Europe/Istanbul');
        $current_time = date("Y-m-d H:i:s");
        
        // gamename yoksa gameid'i kullan
        if ($gamename === null && $gameid !== null) {
            $gamename = $gameid;
        }
        
        // gamename hala null ise boş string kullan
        if ($gamename === null) {
            $gamename = '';
        }
        
        try {
            Transaction::create([
                'user_id'       => $user_id,
                'type'          => $type,
                'amount'        => $amount,
                'created_at'    => $current_time,
                'gameid'        => $gameid,
                'gamename'      => $gamename,
                'transactionid' => $transactionid,
                'session_id'    => $session_id
            ]);
        } catch (\Exception $e) {
            // Hata durumunda sadece zorunlu alanları kaydet
            try {
                Transaction::create([
                    'user_id'    => $user_id,
                    'type'       => $type,
                    'amount'     => $amount,
                    'gamename'   => $gamename,
                    'created_at' => $current_time
                ]);
            } catch (\Exception $e2) {
                // Son çare: sadece en temel alanlar
                Transaction::create([
                    'user_id'    => $user_id,
                    'type'       => $type,
                    'amount'     => $amount,
                    'gamename'   => '',
                    'created_at' => $current_time
                ]);
            }
        }
    }

    /**
     * Webhook handler for Drakon API callbacks
     */
    public function webhook(Request $request)
    {
        // Gelen raw JSON veriyi oku ve logla (eski sistem gibi)
        $rawData = $request->getContent();
        $this->logMessage('Gelen', $rawData);
        
        // Decode
        $data = json_decode($rawData, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->sendResponse('error', 'Invalid input data', 400);
        }

        // İstek işleme (eski sistem gibi)
        if (!isset($data['method'])) {
            return $this->sendResponse('error', 'Method not specified', 400);
        }

        $user_id = isset($data['user_id']) ? (int)$data['user_id'] : null;
        if (!$user_id) {
            return $this->sendResponse('error', 'User ID is required', 400);
        }

        switch ($data['method']) {
            case 'account_details':
                return $this->handleAccountDetails($data);
            
            case 'user_balance':
                return $this->handleUserBalance($data);
            
            case 'transaction_bet':
                return $this->handleTransactionBet($data);
            
            case 'transaction_win':
                return $this->handleTransactionWin($data);
            
            case 'refund':
                return $this->handleRefund($data);
            
            default:
                return $this->sendResponse('error', 'Invalid method', 400);
        }
    }



    /**
     * Handle account details webhook
     * Response: {"email":"","name_jogador":"PlayerName","balance":250.00,"date":1716105600}
     */
    private function handleAccountDetails($data)
    {
        $user_id = $data['user_id'] ?? null;
        
        $user = Admin::find($user_id);
        
        if ($user) {
            return $this->sendResponse('success', '', 200, [
                'email'        => $user->email ?? '',
                'name_jogador' => $user->username,
                'balance'      => number_format((float)$user->bakiye, 2, '.', ''),
                'date'         => time()
            ]);
        } else {
            return $this->sendResponse('error', 'User not found', 404);
        }
    }

    /**
     * Handle user balance webhook
     * Response: {"status":1,"balance":250.00}
     */
    private function handleUserBalance($data)
    {
        $user_id = $data['user_id'] ?? null;
        
        $user = Admin::find($user_id);
        
        if ($user) {
            return $this->sendResponse('success', '', 200, [
                'status'  => 1,
                'balance' => number_format((float)$user->bakiye, 2, '.', '')
            ]);
        } else {
            return $this->sendResponse('error', 'User not found', 404);
        }
    }

    /**
     * Handle bet transaction webhook
     * Request: {"method":"transaction_bet","transaction_id":"trx123","round_id":"rnd456","user_id":123,"bet":50.00,"game":"pg_sweetbonanza"}
     * Response: {"status":1,"balance":200.00}
     */
    private function handleTransactionBet($data)
    {
        $bet_amount = isset($data['bet']) ? (float)$data['bet'] : null;
        if ($bet_amount === null) {
            return $this->sendResponse('error', 'Missing parameter or invalid method', 400);
        }
        
        $gameid        = $data['game'] ?? null;
        $transactionid = $data['transaction_id'] ?? null;
        $round_id      = $data['round_id'] ?? null; // round_id eklendi
        $user_id       = $data['user_id'] ?? null;

        $user = Admin::find($user_id);
        if (!$user) {
            return $this->sendResponse('error', 'User not found', 404);
        }
        
        $new_balance = $user->bakiye - $bet_amount;
        if ($new_balance < 0) {
            return $this->sendResponse('error', 'Insufficient balance', 400);
        }
        
        // Update user balance
        $user->update(['bakiye' => $new_balance]);
        
        // Update cevrim if exists
        if (isset($user->cevrim)) {
            $new_cevrim = max(0, $user->cevrim - $bet_amount);
            $user->update(['cevrim' => $new_cevrim]);
        }
        
        // Log transaction (round_id'yi session_id olarak kaydediyoruz, game'i gamename olarak kullanıyoruz)
        $this->logTransaction($user_id, 'bet', $bet_amount, $gameid, $transactionid, $round_id, $gameid);
        
        return $this->sendResponse('success', '', 200, [
            'status'  => 1,
            'balance' => number_format($new_balance, 2, '.', '')
        ]);
    }

    /**
     * Handle win transaction webhook
     * Request: {"method":"transaction_win","transaction_id":"trx123","round_id":"rnd456","user_id":123,"win":100.00,"game":"pg_sweetbonanza"}
     * Response: {"status":1,"balance":300.00}
     */
    private function handleTransactionWin($data)
    {
        $win_amount = isset($data['win']) ? (float)$data['win'] : null;
        if ($win_amount === null) {
            return $this->sendResponse('error', 'Missing parameter or invalid method', 400);
        }
        
        $gameid        = $data['game'] ?? null;
        $transactionid = $data['transaction_id'] ?? null;
        $round_id      = $data['round_id'] ?? null; // round_id eklendi
        $user_id       = $data['user_id'] ?? null;

        $user = Admin::find($user_id);
        if (!$user) {
            return $this->sendResponse('error', 'User not found', 404);
        }
        
        $new_balance = $user->bakiye + $win_amount;
        
        // Update user balance
        $user->update(['bakiye' => $new_balance]);
        
        // Log transaction (round_id'yi session_id olarak kaydediyoruz, game'i gamename olarak kullanıyoruz)
        $this->logTransaction($user_id, 'win', $win_amount, $gameid, $transactionid, $round_id, $gameid);
        
        return $this->sendResponse('success', '', 200, [
            'status'  => 1,
            'balance' => number_format($new_balance, 2, '.', '')
        ]);
    }

    /**
     * Handle refund webhook
     * Request: {"method":"refund","transaction_id":"trx123","user_id":123,"refund":50.00,"game":"pg_sweetbonanza"}
     * Response: {"status":1,"balance":350.00}
     */
    private function handleRefund($data)
    {
        $refund_amount = isset($data['refund']) ? (float)$data['refund'] : null;
        if ($refund_amount === null) {
            return $this->sendResponse('error', 'Missing parameter or invalid method', 400);
        }
        
        $user_id       = $data['user_id'] ?? null;
        $gameid        = $data['game'] ?? null;
        $transactionid = $data['transaction_id'] ?? null;
        
        $user = Admin::find($user_id);
        if (!$user) {
            return $this->sendResponse('error', 'User not found', 404);
        }
        
        $new_balance = $user->bakiye + $refund_amount;
        
        // Update user balance
        $user->update(['bakiye' => $new_balance]);
        
        // Log transaction (game'i gamename olarak kullanıyoruz)
        $this->logTransaction($user_id, 'refund', $refund_amount, $gameid, $transactionid, null, $gameid);
        
        return $this->sendResponse('success', '', 200, [
            'status'  => 1,
            'balance' => number_format($new_balance, 2, '.', '')
        ]);
    }
} 