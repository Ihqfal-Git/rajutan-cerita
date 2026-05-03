<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — Rajutan Cerita</title>
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

        .icon-wrap { text-align:center; margin-bottom:1.2rem; }
        .icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px; height: 64px;
            border-radius: 50%;
            background: rgba(219,39,119,.12);
            border: 1px solid rgba(219,39,119,.3);
            font-size: 1.8rem;
        }

        .form-title { font-size:1.3rem; font-weight:700; color:#e8e0f0; text-align:center; margin-bottom:.5rem; }
        .form-desc { font-size:.83rem; color:#6b7280; text-align:center; line-height:1.6; margin-bottom:1.8rem; }

        .form-group { margin-bottom:1rem; }

        label { display:block; font-size:.82rem; color:#9ca3af; margin-bottom:.4rem; font-weight:500; }

        input[type=email],
        input[type=password] {
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

        .password-wrap { position: relative; }
        .toggle-pw {
            position: absolute;
            right: .9rem; top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #4b5563;
            cursor: pointer;
            font-size: 1rem;
            padding: 0;
            line-height: 1;
        }
        .toggle-pw:hover { color: #a78bfa; }

        .strength-bar { height: 4px; border-radius: 2px; margin-top: .4rem; background: rgba(150,100,200,.1); overflow: hidden; }
        .strength-fill { height: 100%; border-radius: 2px; transition: width .3s, background .3s; width: 0%; }
        .strength-label { font-size:.7rem; margin-top:.25rem; }

        .input-hint { font-size:.72rem; color:#4b5563; margin-top:.3rem; }
        .field-error { font-size:.75rem; color:#f87171; margin-top:.3rem; }

        .error-banner {
            background: rgba(239,68,68,.12);
            border: 1px solid rgba(239,68,68,.3);
            color: #fca5a5;
            border-radius: 10px;
            padding: .7rem .9rem;
            font-size: .82rem;
            margin-bottom: 1rem;
        }

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

        .links-row {
            display: flex;
            justify-content: center;
            margin-top: 1.2rem;
        }
        .nav-link { font-size:.82rem; color:#6b7280; text-decoration:none; }
        .nav-link:hover { color:#a78bfa; }
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
            <div class="icon-circle">🔐</div>
        </div>

        <h2 class="form-title">Buat Password Baru</h2>
        <p class="form-desc">Masukkan password baru untuk akunmu.</p>

        @if($errors->any())
            <div class="error-banner">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email', $request->email) }}"
                       placeholder="nama@email.com"
                       autocomplete="email" required>
                @error('email') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="password">Password Baru</label>
                <div class="password-wrap">
                    <input type="password" id="password" name="password"
                           placeholder="Min. 8 karakter"
                           autocomplete="new-password"
                           oninput="checkStrength(this.value)"
                           required>
                    <button type="button" class="toggle-pw" onclick="togglePw('password', this)">👁️</button>
                </div>
                <div class="strength-bar">
                    <div class="strength-fill" id="strengthFill"></div>
                </div>
                <div class="strength-label" id="strengthLabel" style="color:#4b5563"></div>
                @error('password') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <div class="password-wrap">
                    <input type="password" id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Ulangi password baru"
                           autocomplete="new-password" required>
                    <button type="button" class="toggle-pw" onclick="togglePw('password_confirmation', this)">👁️</button>
                </div>
                <div class="input-hint" id="matchHint"></div>
            </div>

            <button type="submit" class="btn-submit">Simpan Password Baru</button>
        </form>

        <div class="links-row">
            <a href="{{ route('login') }}" class="nav-link">← Kembali ke Login</a>
        </div>
    </div>

<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    btn.textContent = isHidden ? '🙈' : '👁️';
}

function checkStrength(val) {
    const fill  = document.getElementById('strengthFill');
    const label = document.getElementById('strengthLabel');
    let score = 0;
    if (val.length >= 8)              score++;
    if (/[A-Z]/.test(val))            score++;
    if (/[0-9]/.test(val))            score++;
    if (/[^A-Za-z0-9]/.test(val))     score++;

    const levels = [
        { w:'0%',   color:'transparent', text:'' },
        { w:'25%',  color:'#ef4444',     text:'Lemah' },
        { w:'50%',  color:'#f59e0b',     text:'Cukup' },
        { w:'75%',  color:'#3b82f6',     text:'Bagus' },
        { w:'100%', color:'#10b981',     text:'Kuat 💪' },
    ];

    const lvl = levels[score];
    fill.style.width     = lvl.w;
    fill.style.background = lvl.color;
    label.textContent    = lvl.text;
    label.style.color    = lvl.color;
}

// Cek kecocokan password
document.getElementById('password_confirmation').addEventListener('input', function() {
    const pw  = document.getElementById('password').value;
    const hint = document.getElementById('matchHint');
    if (!this.value) { hint.textContent = ''; return; }
    if (this.value === pw) {
        hint.textContent = '✓ Password cocok';
        hint.style.color = '#6ee7b7';
    } else {
        hint.textContent = '✗ Password tidak cocok';
        hint.style.color = '#f87171';
    }
});
</script>
</body>
</html>