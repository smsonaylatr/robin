<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class K9CasinoController extends Controller
{
    private $apiEndpoint = 'https://api.k9casino.live/api/casinoapi';
    private $agentCode = 'neeko';
    private $apiToken = '6676793163647763707570767039356f';
    private $currency = 'TRY';

    /**
     * K9 Casino API'sine istek gönder
     */
    private function makeApiRequest($method, $params = [])
    {
        $requestData = array_merge([
            'method' => $method,
            'token' => $this->apiToken,
            'agentCode' => $this->agentCode,
        ], $params);

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiEndpoint, $requestData);

            Log::info('K9 Casino API Request', [
                'method' => $method,
                'params' => $params,
                'response' => $response->body()
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('K9 Casino API Error', [
                'method' => $method,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Kullanıcı oluştur
     */
    public function createUser($userCode)
    {
        return $this->makeApiRequest('CreateUser', [
            'userCode' => $userCode
        ]);
    }

    /**
     * Kullanıcı bilgilerini al
     */
    public function getUserInfo($userCode = null)
    {
        $params = [];
        if ($userCode) {
            $params['userCode'] = $userCode;
        }
        
        return $this->makeApiRequest('GetUserInfo', $params);
    }

    /**
     * Kullanıcıya para yatır
     */
    public function deposit($userCode, $amount)
    {
        return $this->makeApiRequest('Deposit', [
            'userCode' => $userCode,
            'currencyCode' => $this->currency,
            'amount' => $amount
        ]);
    }

    /**
     * Kullanıcıdan para çek
     */
    public function withdraw($userCode, $amount)
    {
        return $this->makeApiRequest('Withdraw', [
            'userCode' => $userCode,
            'currencyCode' => $this->currency,
            'amount' => $amount
        ]);
    }

    /**
     * Kullanıcının tüm parasını çek
     */
    public function withdrawAll($userCode)
    {
        return $this->makeApiRequest('WithdrawAll', [
            'userCode' => $userCode,
            'currencyCode' => $this->currency
        ]);
    }

    /**
     * Vendor listesini al
     */
    public function getVendors()
    {
        return $this->makeApiRequest('GetVendors');
    }

    /**
     * Vendor oyunlarını al
     */
    public function getVendorGames($vendorCode)
    {
        return $this->makeApiRequest('GetVendorGames', [
            'vendorCode' => $vendorCode
        ]);
    }

    /**
     * Oyun başlat
     */
    public function launchGame($userCode, $vendorCode, $gameCode = null, $nickname = null)
    {
        $params = [
            'userCode' => $userCode,
            'vendorCode' => $vendorCode,
            'currencyCode' => $this->currency,
            'language' => 'tr',
            'callbackUrl' => url('/k9_api'),
        ];

        if ($gameCode) {
            $params['gameCode'] = $gameCode;
        }

        if ($nickname) {
            $params['nickname'] = $nickname;
        }

        return $this->makeApiRequest('GetGameUrl', $params);
    }

    /**
     * Rapor al (tarih bazlı)
     */
    public function getReportByDate($startDate, $endDate)
    {
        return $this->makeApiRequest('ReportByDate', [
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    /**
     * Rapor al (ID bazlı)
     */
    public function getReportById($startWagerId, $count = 100)
    {
        return $this->makeApiRequest('ReportById', [
            'startWagerId' => $startWagerId,
            'count' => $count
        ]);
    }

    /**
     * Wager detaylarını al
     */
    public function getWagerInfo($wagerId)
    {
        return $this->makeApiRequest('GetWagerInfo', [
            'wagerId' => $wagerId
        ]);
    }

    /**
     * Agent bilgilerini al
     */
    public function getAgentInfo()
    {
        return $this->makeApiRequest('GetAgentInfo');
    }

    /**
     * Wager detay URL'sini al
     */
    public function getDetailUrl($wagerId)
    {
        return $this->makeApiRequest('GetDetailUrl', [
            'wagerId' => $wagerId
        ]);
    }

    /**
     * Oyun başlatma (kısa URL)
     */
    public function directGameLaunch($vendorCode, $gameCode = null)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('login');
        }

        $user = Auth::guard('admin')->user();
        $userCode = 'user_' . $user->id;

        // Önce kullanıcıyı oluştur (eğer yoksa)
        $createUserResponse = $this->createUser($userCode);
        
        if (!$createUserResponse || $createUserResponse['status'] !== 0) {
            Log::warning('K9 Casino user creation failed', [
                'userCode' => $userCode,
                'response' => $createUserResponse
            ]);
        }
        
        // Oyunu başlat
        $launchResponse = $this->launchGame($userCode, $vendorCode, $gameCode, $user->username);
        
        if ($launchResponse && $launchResponse['status'] === 0) {
            return redirect($launchResponse['launchUrl']);
        } else {
            Log::error('K9 Casino game launch failed', [
                'userCode' => $userCode,
                'vendorCode' => $vendorCode,
                'gameCode' => $gameCode,
                'response' => $launchResponse
            ]);
            
            return back()->with('error', 'Oyun başlatılamadı. Lütfen tekrar deneyin.');
        }
    }

    /**
     * API oyun başlatma
     */
    public function gameLaunch($vendorCode, $gameCode = null)
    {
        if (!Auth::guard('admin')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::guard('admin')->user();
        $userCode = 'user_' . $user->id;

        // Önce kullanıcıyı oluştur (eğer yoksa)
        $createUserResponse = $this->createUser($userCode);
        
        if (!$createUserResponse || $createUserResponse['status'] !== 0) {
            Log::warning('K9 Casino user creation failed', [
                'userCode' => $userCode,
                'response' => $createUserResponse
            ]);
        }
        
        // Oyunu başlat
        $launchResponse = $this->launchGame($userCode, $vendorCode, $gameCode, $user->username);
        
        if ($launchResponse && $launchResponse['status'] === 0) {
            return response()->json([
                'success' => true,
                'launchUrl' => $launchResponse['launchUrl']
            ]);
        } else {
            Log::error('K9 Casino game launch failed', [
                'userCode' => $userCode,
                'vendorCode' => $vendorCode,
                'gameCode' => $gameCode,
                'response' => $launchResponse
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Oyun başlatılamadı'
            ], 400);
        }
    }

    /**
     * Webhook callback
     */
    public function webhook(Request $request)
    {
        Log::info('K9 Casino Webhook received', [
            'method' => $request->method(),
            'data' => $request->all()
        ]);

        $data = $request->all();
        
        if (!isset($data['method'])) {
            return response()->json(['status' => 2, 'msg' => 'INVALID_ACTION'], 400);
        }

        switch ($data['method']) {
            case 'GetBalance':
                return $this->handleGetBalance($data);
                
            case 'ChangeBalance':
                return $this->handleChangeBalance($data);
                
            case 'UpdateDetail':
                return $this->handleUpdateDetail($data);
                
            default:
                return response()->json(['status' => 2, 'msg' => 'INVALID_ACTION'], 400);
        }
    }

    /**
     * Bakiye sorgulama
     */
    private function handleGetBalance($data)
    {
        if (!isset($data['userCode']) || !isset($data['currencyCode'])) {
            return response()->json(['status' => 13, 'msg' => 'INVALID_PARAMETER'], 400);
        }

        // userCode'dan user ID'yi çıkar (user_123 formatından)
        $userCode = $data['userCode'];
        if (strpos($userCode, 'user_') === 0) {
            $userId = (int)substr($userCode, 5);
        } else {
            return response()->json(['status' => 5, 'msg' => 'INVALID_USER'], 404);
        }

        $user = \App\Models\Admin::find($userId);
        if (!$user) {
            return response()->json(['status' => 5, 'msg' => 'INVALID_USER'], 404);
        }

        return response()->json([
            'status' => 0,
            'msg' => 'SUCCESS',
            'balance' => (float)$user->bakiye
        ]);
    }

    /**
     * Bakiye değişikliği
     */
    private function handleChangeBalance($data)
    {
        if (!isset($data['userCode']) || !isset($data['currencyCode']) || !isset($data['amount']) || !isset($data['wagerId'])) {
            return response()->json(['status' => 13, 'msg' => 'INVALID_PARAMETER'], 400);
        }

        // userCode'dan user ID'yi çıkar
        $userCode = $data['userCode'];
        if (strpos($userCode, 'user_') === 0) {
            $userId = (int)substr($userCode, 5);
        } else {
            return response()->json(['status' => 5, 'msg' => 'INVALID_USER'], 404);
        }

        $user = \App\Models\Admin::find($userId);
        if (!$user) {
            return response()->json(['status' => 5, 'msg' => 'INVALID_USER'], 404);
        }

        $amount = (float)$data['amount'];
        $wagerId = $data['wagerId'];
        $txnType = $data['txnType'] ?? 0; // 0: Debit, 1: Credit, 2: Cancel
        $vendorCode = $data['vendorCode'] ?? '';
        $gameCode = $data['gameCode'] ?? '';
        $gameRoundId = $data['gameRoundId'] ?? '';

        // Bakiye kontrolü (Debit için)
        if ($txnType == 0 && $user->bakiye < abs($amount)) {
            return response()->json(['status' => 8, 'msg' => 'INSUFFICIENT_MONEY'], 400);
        }

        // Bakiye güncelle
        $newBalance = $user->bakiye + $amount;
        $user->bakiye = $newBalance;
        $user->save();

        // Transaction kaydet
        $transactionType = $txnType == 0 ? 'bet' : ($txnType == 1 ? 'win' : 'refund');
        $description = "K9 Casino - {$vendorCode} - {$gameCode}";
        
        \App\Models\Transaction::create([
            'user_id' => $userId,
            'type' => $transactionType,
            'amount' => abs($amount),
            'description' => $description,
            'gameid' => $gameCode,
            'transactionid' => $wagerId,
            'session_id' => $gameRoundId,
            'created_at' => now()
        ]);

        return response()->json([
            'status' => 0,
            'msg' => 'SUCCESS',
            'balance' => (float)$newBalance
        ]);
    }

    /**
     * Detay güncelleme
     */
    private function handleUpdateDetail($data)
    {
        if (!isset($data['wagerId']) || !isset($data['detail'])) {
            return response()->json(['status' => 13, 'msg' => 'INVALID_PARAMETER'], 400);
        }

        // Transaction'ı güncelle
        $transaction = \App\Models\Transaction::where('transactionid', $data['wagerId'])->first();
        if ($transaction) {
            $transaction->additional_data = $data['detail'];
            $transaction->save();
        }

        return response()->json([
            'status' => 0,
            'msg' => 'SUCCESS'
        ]);
    }
} 