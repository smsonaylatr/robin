<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Şifre Sıfırlama</title>
    <style>
        body { background:#0b0b0b; color:#e5e7eb; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
        .card { max-width:560px; margin:24px auto; background:#111; border:1px solid #27272a; border-radius:12px; padding:20px; }
        .title { color:#ef4444; font-weight:700; font-size:18px; margin:0 0 8px; }
        .muted { color:#a1a1aa; font-size:14px; margin: 0 0 16px; }
        .pill { display:inline-block; padding:10px 14px; border-radius:10px; background:#18181b; border:1px solid #27272a; color:#fff; font-weight:600; letter-spacing:.3px }
        .row { margin: 16px 0 }
        .code { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; }
        .sep { height:1px; background:#27272a; margin:16px 0 }
    </style>
  </head>
  <body>
    <div class="card">
        <h1 class="title">Şifreniz güncellendi</h1>
        <p class="muted">Aşağıda yeni giriş bilgilerinizi bulabilirsiniz. Güvenliğiniz için giriş yaptıktan sonra şifrenizi değiştirmenizi öneririz.</p>

        <div class="row">
            <div class="pill">Kullanıcı Adı: <span class="code">{{ $username }}</span></div>
        </div>
        <div class="row">
            <div class="pill">Yeni Şifre: <span class="code">{{ $password }}</span></div>
        </div>

        <div class="sep"></div>

        <p class="muted">Bu işlemi siz yapmadıysanız lütfen destek ekibimizle iletişime geçin.</p>
    </div>
  </body>
 </html>

