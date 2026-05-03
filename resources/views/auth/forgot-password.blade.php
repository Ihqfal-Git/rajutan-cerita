<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — Rajutan Cerita</title>
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
            background: radial-gradient(ellipse, rgba(124,58,237,.1), transparent 70%);
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

        .icon-wrap {
            text-align: center;
            margin-bottom: 1.2rem;
        }
        .icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(124,58,237,.15);
            border: 1px solid rgba(124,58,237,.3);
            font-size: 1.8rem;
        }

        .form-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #e8e0f0;
            text-align: center;
            margin-bottom: .5rem;
        }
        .form-desc {
            font-size: .83rem;
            color: #6b7280;
            text-align: center;
            line-height: 1.6;
            margin-bottom: 1.8rem;
        }

        .form-group { margin-bottom: 1.2rem; }

        label {
            display: block;
            font-size: .82rem;
            color: #9ca3af;
            margin-bottom: .4rem;
            font-weight: 500;
        }

        input[type=email] {
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
        }
        .btn-submit:hover { transform:translateY(-1px); box-shadow:0 6px 24px rgba(124,58,237,.4); }
        .btn-submit:active { transform:translateY(0); }

        .status-msg {
            background: rgba(16,185,129,.12);
            border: 1px solid rgba(16,185,129,.3);
            color: #6ee7b7;
            border-radius: 12px;
            padding: 1rem;
            font-size: .85rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: flex-start;
            gap: .6rem;
            line-height: 1.5;
        }
        .status-icon { font-size: 1.1rem; flex-shrink:0; }

        .error-banner {
            background: rgba(239,68,68,.12);
            border: 1px solid rgba(239,68,68,.3);
            color: #fca5a5;
            border-radius: 10px;
            padding: .7rem .9rem;
            font-size: .82rem;
            margin-bottom: 1rem;
        }

        .links-row {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 1.2rem;
            flex-wrap: wrap;
        }
        .nav-link {
            font-size: .82rem;
            color: #6b7280;
            text-decoration: none;
            transition: color .2s;
        }
        .nav-link:hover { color: #a78bfa; }
        .nav-link.accent { color: #a78bfa; }
        .nav-link.accent:hover { color: #c084fc; }
    </style>
</head>
<body>
    <div class="glow"></div>

    <div class="card">
        <div class="logo">
            <span class="logo-text">🧶 Rajutan Cerita</span>
            <span class="logo-sub">Platform kenangan digitalmu</span>
        </div>

        <div class="icon-wrap">
            <div class="icon-circle">🔑</div>
        </div>

        <h2 class="form-title">Lupa Password?</h2>
        <p class="form-desc">
            Masukkan email yang terdaftar. Kami akan mengirimkan link untuk mereset passwordmu.
        </p>

        @if(session('status'))
            <div class="status-msg">
                <span class="status-icon">✅</span>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="error-banner">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="nama@email.com"
                       autocomplete="email" required autofocus>
                @error('email') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn-submit">
                Kirim Link Reset Password
            </button>
        </form>

        <div class="links-row">
            <a href="{{ route('login') }}" class="nav-link accent">← Kembali ke Login</a>
            <a href="/" class="nav-link">Beranda</a>
        </div>
    </div>
</body>
</html>