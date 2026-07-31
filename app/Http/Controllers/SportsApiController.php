<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\SportsApi;
use Illuminate\Support\Facades\Log;

class SportsApiController extends Controller
{
    /**
     * Validate callback from sports provider
     */
public function validateCallback(Request $request): JsonResponse
{
    try {
        // Zorunlu alanlar (agent_code kaldırıldı)
        $request->validate([
            'api_secret_key' => 'required|string',
            'api_token' => 'required|string',
            'username' => 'required|string',
            'user_id' => 'required|integer'
        ]);

        $sportsApi = SportsApi::getActiveApi();
        if (!$sportsApi) {
            return response()->json([
                'success' => false,
                'error' => 'API yapılandırması bulunamadı'
            ], 500);
        }

        // Credentials kontrolü (agent_code kontrolü kaldırıldı)
        if ($sportsApi->api_secret_key !== $request->api_secret_key ||
            $sportsApi->api_token !== $request->api_token) {
            return response()->json([
                'success' => false,
                'error' => 'Geçersiz API bilgileri'
            ], 401);
        }

        // Session ve secure token üret
        $sessionToken = bin2hex(random_bytes(32));
        $secureToken = bin2hex(random_bytes(32));
        session([
            'sports_session_token' => $sessionToken,
            'sports_secure_token' => $secureToken,
            'sports_user_id' => $request->user_id,
            'sports_username' => $request->username
        ]);

        return response()->json([
            'success' => true,
            'session_token' => $sessionToken,
            'secure_token' => $secureToken,
            'redirect_url' => $sportsApi->api_url,
            'message' => 'Doğrulama başarılı'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Sunucu hatası: ' . $e->getMessage()
        ], 500);
    
        }
    }
}
