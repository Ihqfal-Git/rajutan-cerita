<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rajutan Cerita — Simpan Kenangan Digitalmu</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #0f0f1a;
            color: #e8e0f0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* NAV */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 2.5rem;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            background: rgba(15, 15, 26, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(150, 100, 200, 0.1);
        }

        .brand {
            font-size: 1.3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #c084fc, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .nav-links { display: flex; gap: 0.8rem; }

        .btn {
            padding: 0.5rem 1.3rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            display: inline-block;
        }

        .btn-ghost {
            color: #a78bfa;
            background: transparent;
            border: 1px solid rgba(167, 139, 250, 0.3);
        }
        .btn-ghost:hover { background: rgba(167, 139, 250, 0.1); }

        .btn-primary {
            background: linear-gradient(135deg, #7c3aed, #db2777);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(124, 58, 237, 0.4);
        }

        /* HERO */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 8rem 2rem 4rem;
            position: relative;
        }

        .hero-glow {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(ellipse, rgba(124, 58, 237, 0.12), transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -55%);
            pointer-events: none;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(124, 58, 237, 0.15);
            border: 1px solid rgba(192, 132, 252, 0.3);
            color: #c084fc;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 0.35rem 1rem;
            border-radius: 20px;
            margin-bottom: 1.5rem;
        }

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 1.2rem;
            background: linear-gradient(160deg, #ffffff 30%, #c084fc 70%, #f472b6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-sub {
            font-size: 1.1rem;
            color: #9ca3af;
            max-width: 500px;
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-large {
            padding: 0.85rem 2.2rem;
            font-size: 1rem;
            border-radius: 30px;
        }

        .hero-note {
            margin-top: 1.2rem;
            font-size: 0.78rem;
            color: #4b5563;
        }

        /* FEATURES */
        .features {
            padding: 5rem 2rem;
            max-width: 960px;
            margin: 0 auto;
        }

        .section-label {
            text-align: center;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #7c3aed;
            font-weight: 600;
            margin-bottom: 0.8rem;
        }

        .section-title {
            text-align: center;
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            font-weight: 700;
            color: #e8e0f0;
            margin-bottom: 3rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.2rem;
        }

        .feature-card {
            background: rgba(25, 15, 45, 0.7);
            border: 1px solid rgba(150, 100, 200, 0.12);
            border-radius: 16px;
            padding: 1.5rem;
            transition: border-color 0.3s;
        }

        .feature-card:hover {
            border-color: rgba(192, 132, 252, 0.3);
        }

        .feature-icon {
            font-size: 1.8rem;
            margin-bottom: 0.8rem;
            display: block;
        }

        .feature-name {
            font-weight: 600;
            font-size: 1rem;
            color: #e8e0f0;
            margin-bottom: 0.4rem;
        }

        .feature-desc {
            font-size: 0.85rem;
            color: #6b7280;
            line-height: 1.6;
        }

        /* TYPES PREVIEW */
        .types-section {
            padding: 4rem 2rem;
            background: rgba(20, 10, 40, 0.5);
        }

        .types-inner {
            max-width: 960px;
            margin: 0 auto;
        }

        .types-grid {
            display: flex;
            gap: 0.8rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .type-pill {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            background: rgba(30, 15, 55, 0.8);
            border: 1px solid rgba(150, 100, 200, 0.2);
            border-radius: 30px;
            font-size: 0.9rem;
            color: #c084fc;
            font-weight: 500;
        }

        /* CTA BOTTOM */
        .cta-bottom {
            padding: 6rem 2rem;
            text-align: center;
        }

        .cta-box {
            max-width: 540px;
            margin: 0 auto;
            background: rgba(25, 15, 45, 0.8);
            border: 1px solid rgba(150, 100, 200, 0.2);
            border-radius: 24px;
            padding: 3rem 2rem;
        }

        .cta-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #e8e0f0;
            margin-bottom: 0.8rem;
        }

        .cta-sub {
            color: #6b7280;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        /* FOOTER */
        footer {
            text-align: center;
            padding: 1.5rem;
            color: #374151;
            font-size: 0.8rem;
            border-top: 1px solid rgba(150, 100, 200, 0.08);
        }

        @media (max-width: 480px) {
            nav { padding: 1rem 1.2rem; }
            .hero { padding: 7rem 1.2rem 3rem; }
            .btn-large { padding: 0.75rem 1.6rem; font-size: 0.9rem; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav>
        <a href="/" class="brand">🧶 Rajutan Cerita</a>
        <div class="nav-links">
            <a href="/login" class="btn btn-ghost">Masuk</a>
            <a href="/register" class="btn btn-primary">Daftar Gratis</a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-glow"></div>
        <div class="hero-badge">✨ Platform Kenangan Digitalmu</div>
        <h1 class="hero-title">Simpan Setiap<br>Momen Berharga</h1>
        <p class="hero-sub">
            Rajutan Cerita adalah tempatmu menyimpan kenangan dalam berbagai bentuk
            foto, video, musik, tautan, atau sekadar tulisan dari hati.
        </p>
        <div class="hero-cta">
            <a href="/register" class="btn btn-primary btn-large">Mulai Sekarang</a>
            <a href="/login" class="btn btn-ghost btn-large">Sudah punya akun</a>
        </div>
        <p class="hero-note">Gratis selamanya · Privat · Modern</p>
    </section>

    <!-- FEATURES -->
    <section class="features">
        <p class="section-label">Kenapa Rajutan Cerita?</p>
        <h2 class="section-title">Semua yang kamu butuhkan</h2>
        <div class="features-grid">
            <div class="feature-card">
                <span class="feature-icon">🔒</span>
                <div class="feature-name">Modern</div>
                <div class="feature-desc">Desain yang terbaru dan menarik untuk pengalaman pengguna yang optimal.</div>
            </div>
            <div class="feature-card">
                <span class="feature-icon">🎨</span>
                <div class="feature-name">Fleksibel</div>
                <div class="feature-desc">Simpan dalam bentuk foto, video, musik, link, atau teks bebas.</div>
            </div>
            <div class="feature-card">
                <span class="feature-icon">📱</span>
                <div class="feature-name">Nyaman di HP</div>
                <div class="feature-desc">Tampilan yang bersih dan responsif, enak dipakai dari mana saja.</div>
            </div>
            <div class="feature-card">
                <span class="feature-icon">⚡</span>
                <div class="feature-name">Mudah Dipakai</div>
                <div class="feature-desc">Tambah, edit, hapus kenangan dalam hitungan detik tanpa ribet.</div>
            </div>
        </div>
    </section>

    <!-- TYPES -->
    <section class="types-section">
        <div class="types-inner">
            <p class="section-label">Format yang Didukung</p>
            <h2 class="section-title">Kenangan dalam berbagai wujud</h2>
            <div class="types-grid">
                <div class="type-pill">🖼️ Foto</div>
                <div class="type-pill">🎬 Video</div>
                <div class="type-pill">🎵 Musik</div>
                <div class="type-pill">🔗 Link</div>
                <div class="type-pill">📝 Tulisan</div>
            </div>
        </div>
    </section>

    <!-- CTA BOTTOM -->
    <section class="cta-bottom">
        <div class="cta-box">
            <h2 class="cta-title">Mulai rajut ceritamu 🧶</h2>
            <p class="cta-sub">Daftar gratis dan simpan kenangan pertamamu hari ini.</p>
            <a href="/register" class="btn btn-primary btn-large">Buat Akun Gratis</a>
        </div>
    </section>

    <footer>
        &copy; {{ date('Y') }} Rajutan Cerita — Dibuat dengan hati
    </footer>

</body>
</html>