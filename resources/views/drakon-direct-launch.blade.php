<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oyun Başlatılıyor - BetNow</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #1a1a1a;
            color: white;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
        .loading {
            margin: 40px 0;
        }
        .spinner {
            border: 4px solid #333;
            border-top: 4px solid #ff4444;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .error {
            background: #fdecea;
            color: #611a15;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .user-info {
            background: #f5f5f5;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .back-button {
            background: #ff4444;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        .back-button:hover {
            background: #cc3333;
        }
        .debug-info {
            background: #2a2a2a;
            border: 1px solid #444;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            font-family: monospace;
            font-size: 12px;
            color: #ccc;
            text-align: left;
            max-height: 300px;
            overflow-y: auto;
        }
        .debug-title {
            color: #ffaa00;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    @if(isset($user))
    <div class="user-info">
        <div>Hoş geldin, <strong>{{ $user->username }}</strong></div>
        <div>Bakiye: <strong>{{ number_format($user->bakiye, 2, ',', '.') }} TL</strong></div>
    </div>
    @endif

    <div class="container">
        @if(isset($gameUrl) && $gameUrl)
            <h2>Oyun Başlatılıyor...</h2>
            <div class="loading">
                <div class="spinner"></div>
                <p>Lütfen bekleyin, oyun yükleniyor...</p>
            </div>
        @else
            <h2>Hata</h2>
            <div class="error">
                {{ $error ?? 'Oyun başlatılamadı. Lütfen tekrar deneyin.' }}
            </div>
            
            @if(isset($debug_info))
            <div class="debug-info">
                <div class="debug-title">Debug Bilgileri:</div>
                <pre>{{ json_encode($debug_info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
            @endif
            
            <button class="back-button" onclick="window.history.back()">Geri Dön</button>
        @endif
    </div>

    @if(isset($gameUrl) && $gameUrl)
    <script>
        // URL'yi decode et ve JavaScript'e aktar
        var gameUrl = @json($gameUrl);
        
        // Oyunu aynı sayfada aç
        setTimeout(() => {
            window.location.href = gameUrl;
        }, 2000);
    </script>
    @endif
</body>
</html> 