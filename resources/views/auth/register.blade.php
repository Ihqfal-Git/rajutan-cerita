<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Rajutan Cerita</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #0f0f1a;
            color: #e8e0f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .glow {
            position: fixed;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(ellipse, rgba(219,39,119,.08), transparent 70%);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .card {
            background: rgba(20,12,40,.9);
            border: 1px solid rgba(150,100,200,.2);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        .logo { text-align:center; margin-bottom:2rem; }
        .logo-text {
            font-size: 1.6rem;
            font-weight: 800;
            background: linear-gradient(135deg, #c084fc, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: block;
            margin-bottom: .3rem;
        }
        .logo-sub { font-size:.82rem; color:#4b5563; }

        .form-title { font-size:1.3rem; font-weight:700; color:#e8e0f0; margin-bottom:1.5rem; text-align:center; }

        .form-group { margin-bottom:1rem; }

        label { display:block; font-size:.82rem; color:#9ca3af; margin-bottom:.4rem; font-weight:500; }

        input[type=email],
        input[type=password],
        input[type=text] {
            width: 100%;
            background: rgba(15,10,30,.8);
            border: 1px solid rgba(150,100,200,.25);
            border-radius: 10px;
            color: #e8e0f0;
            padding: .75rem 1rem;
            font-size: .9rem;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            font-family: inherit;
        }
        input:focus {
            border-color: rgba(192,132,252,.6);
            box-shadow: 0 0 0 3px rgba(124,58,237,.1);
        }

        .input-hint { font-size:.72rem; color:#4b5563; margin-top:.3rem; }

        .field-error { font-size:.75rem; color:#f87171; margin-top:.3rem; }

        .btn-submit {
            width: 100%;
            padding: .85rem;
            background: linear-gradient(135deg, #7c3aed, #db2777);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s;
            font-family: inherit;
            margin-top: .5rem;
        }
        .btn-submit:hover { transform:translateY(-1px); box-shadow:0 6px 24px rgba(124,58,237,.4); }

        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.2rem 0;
            color: #374151;
            font-size: .78rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(150,100,200,.15);
        }

        .login-link { text-align:center; font-size:.85rem; color:#6b7280; }
        .login-link a { color:#a78bfa; text-decoration:none; font-weight:600; }
        .login-link a:hover { color:#c084fc; }

        .error-banner {
            background: rgba(239,68,68,.12);
            border: 1px solid rgba(239,68,68,.3);
            color: #fca5a5;
            border-radius: 10px;
            padding: .7rem .9rem;
            font-size: .82rem;
            margin-bottom: 1rem;
        }

        .back-home {
            display: block;
            text-align: center;
            margin-top: 1.2rem;
            font-size: .78rem;
            color: #374151;
            text-decoration: none;
        }
        .back-home:hover { color: #6b7280; }

        .perks {
            display: flex;
            justify-content: center;
            gap: 1.2rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        .perk {
            display: flex;
            align-items: center;
            gap: .3rem;
            font-size: .72rem;
            color: #4b5563;
        }
        .perk-icon { font-size: .85rem; }
    </style>
</head>
<body>
    <div class="glow"></div>

    <div class="card">
        <div class="logo">
            <span class="logo-text">🧶 Rajutan Cerita</span>
            <span class="logo-sub">Platform kenangan digitalmu</span>
        </div>

        <h2 class="form-title">Buat akun gratis</h2>

        <div class="perks">
            <span class="perk"><span class="perk-icon">🔒</span> 100% Privat</span>
            <span class="perk"><span class="perk-icon">✨</span> Gratis selamanya</span>
            <span class="perk"><span class="perk-icon">🧶</span> QR kenangan</span>
        </div>

        @if($errors->any())
            <div class="error-banner">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name') }}"
                       placeholder="Nama lengkapmu"
                       autocomplete="name" required>
                @error('name') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="nama@email.com"
                       autocomplete="email" required>
                @error('email') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="Min. 8 karakter"
                       autocomplete="new-password" required>
                <div class="input-hint">Minimal 8 karakter</div>
                @error('password') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation"
                       name="password_confirmation"
                       placeholder="Ulangi password"
                       autocomplete="new-password" required>
            </div>

            <button type="submit" class="btn-submit">Buat Akun</button>
        </form>

        <div class="divider">sudah punya akun?</div>

        <div class="login-link">
            <a href="{{ route('login') }}">Masuk sekarang</a>
        </div>

        <a href="/" class="back-home">← Kembali ke beranda</a>
    </div>
</body>
</html>