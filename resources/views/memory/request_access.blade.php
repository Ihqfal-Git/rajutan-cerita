<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minta Akses — Rajutan Cerita</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',system-ui,sans-serif; background:#0f0f1a; color:#e8e0f0; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:2rem; }

        .lock-card { background:rgba(25,15,45,.9); border:1px solid rgba(150,100,200,.2); border-radius:24px; padding:2.5rem 2rem; max-width:420px; width:100%; text-align:center; }

        .lock-icon { font-size:4rem; margin-bottom:1rem; animation:pulse 2s ease-in-out infinite; }
        @keyframes pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.05)} }

        .lock-title { font-size:1.5rem; font-weight:700; color:#e8e0f0; margin-bottom:.5rem; }
        .lock-owner { color:#a78bfa; font-size:.9rem; margin-bottom:.8rem; }
        .lock-desc { color:#6b7280; font-size:.85rem; line-height:1.6; margin-bottom:2rem; }

        .status-box { background:rgba(15,10,30,.6); border-radius:12px; padding:1rem; margin-bottom:1.5rem; }
        .status-pending { border:1px solid rgba(234,179,8,.3); }
        .status-rejected { border:1px solid rgba(239,68,68,.3); }
        .status-text-pending { color:#fbbf24; font-size:.85rem; }
        .status-text-rejected { color:#f87171; font-size:.85rem; }

        .form-group { margin-bottom:1rem; text-align:left; }
        label { display:block; font-size:.82rem; color:#9ca3af; margin-bottom:.4rem; }
        input,textarea { width:100%; background:rgba(15,10,30,.8); border:1px solid rgba(150,100,200,.25); border-radius:10px; color:#e8e0f0; padding:.75rem 1rem; font-size:.9rem; outline:none; font-family:inherit; }
        input:focus { border-color:rgba(192,132,252,.6); }

        .btn-submit { width:100%; padding:.85rem; background:linear-gradient(135deg,#7c3aed,#db2777); color:white; border:none; border-radius:20px; font-size:1rem; font-weight:600; cursor:pointer; transition:all .2s; }
        .btn-submit:hover { transform:translateY(-1px); box-shadow:0 4px 20px rgba(124,58,237,.4); }

        .brand-footer { margin-top:1.5rem; }
        .brand-link { color:#4b5563; font-size:.78rem; text-decoration:none; }
        .brand-link:hover { color:#a78bfa; }

        .alert { padding:.8rem 1rem; border-radius:10px; margin-bottom:1rem; font-size:.85rem; }
        .alert-success { background:rgba(16,185,129,.15); border:1px solid rgba(16,185,129,.3); color:#6ee7b7; }
        .alert-info { background:rgba(99,102,241,.15); border:1px solid rgba(99,102,241,.3); color:#a5b4fc; }
    </style>
</head>
<body>
    <div class="lock-card">
        <div class="lock-icon">🔒</div>
        <h1 class="lock-title">{{ $memory->title }}</h1>
        <p class="lock-owner">Kenangan milik {{ $memory->user->name }}</p>
        <p class="lock-desc">Kenangan ini bersifat privat. Minta izin kepada pemiliknya untuk melihat isi kenangan ini.</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        @if($accessRequest)
            @if($accessRequest->status === 'pending')
                <div class="status-box status-pending">
                    <p class="status-text-pending">⏳ Permintaanmu sedang menunggu persetujuan pemilik...</p>
                </div>
            @elseif($accessRequest->status === 'rejected')
                <div class="status-box status-rejected">
                    <p class="status-text-rejected">❌ Permintaan aksesmu telah ditolak oleh pemilik kenangan.</p>
                </div>
            @endif
        @else
            <form method="POST" action="{{ route('access.request', $memory->slug) }}">
                @csrf
                <div class="form-group">
                    <label>Nama kamu</label>
                    <input type="text" name="guest_name" placeholder="Siapa namamu?" required value="{{ old('guest_name') }}">
                    @error('guest_name') <div style="color:#f87171;font-size:.78rem;margin-top:.3rem">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn-submit">🙏 Minta Akses</button>
            </form>
        @endif

        <div class="brand-footer">
            <a href="/" class="brand-link">🧶 Rajutan Cerita</a>
        </div>
    </div>
</body>
</html>