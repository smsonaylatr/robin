<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BETCO API Test - Partner Integration</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 800px;
            width: 100%;
            padding: 40px;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .section h2 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
            font-size: 13px;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border 0.3s;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
        }

        button {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.3s;
        }

        button:hover {
            background: #5568d3;
        }

        button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .response {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            display: none;
        }

        .response.success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .response.error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        pre {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            margin-top: 10px;
            font-size: 12px;
        }

        .iframe-container {
            margin-top: 20px;
            display: none;
        }

        .iframe-container iframe {
            width: 100%;
            height: 800px;
            border: 2px solid #667eea;
            border-radius: 8px;
        }

        .info-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .info-box strong {
            color: #856404;
        }

        .credentials {
            background: white;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .credentials p {
            margin: 5px 0;
            font-family: monospace;
            font-size: 13px;
        }

        .credentials strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 BETCO API Test</h1>
        <p class="subtitle">Partner entegrasyonu için API authentication testi</p>

        <!-- Bilgilendirme -->
        <div class="info-box">
            <strong>ℹ️ Test Credentials:</strong>
            <div class="credentials">
                <p><strong>API Token:</strong> test_token_123456789abcdef</p>
                <p><strong>API Secret:</strong> test_secret_987654321fedcba</p>
                <p><strong>Domain:</strong> localhost</p>
            </div>
        </div>

        <!-- Test Formu -->
        <div class="section">
            <h2>1️⃣ API Login Test</h2>
            
            <div class="form-group">
                <label>User ID (Partner sistemindeki kullanıcı ID)</label>
                <input type="text" id="user_id" value="12345" placeholder="12345">
            </div>

            <div class="form-group">
                <label>Username (Kullanıcı adı)</label>
                <input type="text" id="username" value="test_user" placeholder="test_user">
            </div>

            <div class="form-group">
                <label>API Token</label>
                <input type="text" id="api_token" value="test_token_123456789abcdef" placeholder="API Token">
            </div>

            <div class="form-group">
                <label>API Secret</label>
                <input type="text" id="api_secret" value="test_secret_987654321fedcba" placeholder="API Secret">
            </div>

            <button onclick="testLogin()" id="login-btn">🚀 Giriş Yap</button>

            <div id="login-response" class="response"></div>
        </div>

        <!-- iFrame Test -->
        <div class="section">
            <h2>2️⃣ iFrame Test (Giriş Yaptıktan Sonra)</h2>
            <p style="color: #666; font-size: 13px; margin-bottom: 15px;">
                Başarılı girişten sonra session token ile iframe açılacak
            </p>
            <button onclick="openIframe()" id="iframe-btn" disabled>📺 iFrame Aç</button>
        </div>

        <div class="iframe-container" id="iframe-container"></div>
    </div>

  <script>
let sessionToken = null;

function testLogin() {
    const btn = document.getElementById('login-btn');
    const response = document.getElementById('login-response');
    
    btn.disabled = true;
    btn.textContent = '⏳ Gönderiliyor...';
    
    const data = {
        user_id: document.getElementById('user_id').value,
        username: document.getElementById('username').value,
        api_token: document.getElementById('api_token').value,
        api_secret: document.getElementById('api_secret').value
    };

    fetch('https://sports.paybetcasinoservices.com/api/auth/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
        response.style.display = 'block';
        
        if (result.success) {
            response.className = 'response success';
            response.innerHTML = `
                <strong>✅ Başarılı!</strong><br>
                Session Token: <code>${result.data.session_token}</code><br>
                Kullanıcı: ${result.data.username}<br>
                Partner: ${result.data.partner}<br>
                Geçerlilik: ${result.data.expires_at}
                <pre>${JSON.stringify(result, null, 2)}</pre>
            `;
            
            sessionToken = result.data.session_token;
            document.getElementById('iframe-btn').disabled = false;
        } else {
            response.className = 'response error';
            response.innerHTML = `
                <strong>❌ Hata!</strong><br>
                ${(result.error || 'Bilinmeyen hata')}
                <pre>${JSON.stringify(result, null, 2)}</pre>
            `;
        }
        
        btn.disabled = false;
        btn.textContent = '🚀 Giriş Yap';
    })
    .catch(error => {
        response.style.display = 'block';
        response.className = 'response error';
        response.innerHTML = `<strong>❌ Network Hatası!</strong><br>${error.message}`;
        
        btn.disabled = false;
        btn.textContent = '🚀 Giriş Yap';
    });
}

function openIframe() {
    if (!sessionToken) {
        alert('Önce giriş yapmalısınız!');
        return;
    }

    const container = document.getElementById('iframe-container');
    
    // 🔧 Burada kendi domainini değil, hedef domaini kullanıyoruz
    const baseUrl = 'https://sports.paybetcasinoservices.com';
    
    // Live veya Presports seçimi
    const page = confirm('Live sayfası için OK, Presports için Cancel basın');
    const pageUrl = page ? '/tr/live' : '/tr/presports';
    
    const fullUrl = `${baseUrl}${pageUrl}?session_token=${sessionToken}`;
    
    container.innerHTML = `
        <h3 style="margin-bottom: 10px; color: #667eea;">iFrame Görünümü</h3>
        <p style="color: #666; font-size: 13px; margin-bottom: 10px;">
            Session Token: <code>${sessionToken}</code>
        </p>
        <iframe src="${fullUrl}" allowfullscreen></iframe>
    `;
    container.style.display = 'block';
}
</script>

</body>
</html>

