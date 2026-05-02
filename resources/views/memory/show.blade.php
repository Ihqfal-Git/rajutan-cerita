@extends('layouts.app')

@section('content')
<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        text-decoration: none;
        font-size: 0.85rem;
        margin-bottom: 1.5rem;
        transition: color 0.2s;
    }

    .back-link:hover { color: #a78bfa; }

    .detail-card {
        background: rgba(25, 15, 45, 0.9);
        border: 1px solid rgba(150, 100, 200, 0.2);
        border-radius: 20px;
        overflow: hidden;
        max-width: 700px;
        margin: 0 auto;
    }

    .detail-media {
        width: 100%;
        background: rgba(15, 10, 30, 0.8);
    }

    .detail-media img {
        width: 100%;
        max-height: 400px;
        object-fit: contain;
        display: block;
    }

    .detail-media video, .detail-media audio {
        width: 100%;
        display: block;
    }

    .media-placeholder {
        padding: 3rem;
        text-align: center;
        font-size: 4rem;
    }

    .detail-body { padding: 1.8rem; }

    .detail-type {
        font-size: 0.75rem;
        color: #7c3aed;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .detail-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #e8e0f0;
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .detail-desc {
        color: #9ca3af;
        line-height: 1.8;
        font-size: 0.95rem;
        white-space: pre-wrap;
        margin-bottom: 1.5rem;
    }

    .detail-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #818cf8;
        text-decoration: none;
        background: rgba(99, 102, 241, 0.1);
        border: 1px solid rgba(99, 102, 241, 0.2);
        padding: 0.6rem 1rem;
        border-radius: 10px;
        font-size: 0.85rem;
        margin-bottom: 1.5rem;
        word-break: break-all;
        transition: all 0.2s;
    }

    .detail-link:hover {
        background: rgba(99, 102, 241, 0.2);
    }

    .detail-meta {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        padding-top: 1rem;
        border-top: 1px solid rgba(150, 100, 200, 0.1);
        font-size: 0.8rem;
        color: #4b5563;
    }

    .detail-actions {
        display: flex;
        gap: 0.8rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(150, 100, 200, 0.1);
        flex-wrap: wrap;
    }
</style>

<a href="/home" class="back-link">← Kembali</a>

<div class="detail-card">
    <div class="detail-media">
        @if($memory->type === 'image' && $memory->file_path)
            <img src="{{ asset('storage/' . $memory->file_path) }}" alt="{{ $memory->title }}">
        @elseif($memory->type === 'video' && $memory->file_path)
            <video controls><source src="{{ asset('storage/' . $memory->file_path) }}">Browser tidak mendukung video.</video>
        @elseif($memory->type === 'music' && $memory->file_path)
            <div class="media-placeholder">🎵</div>
            <audio controls style="width:100%;padding:1rem"><source src="{{ asset('storage/' . $memory->file_path) }}">Browser tidak mendukung audio.</audio>
        @elseif($memory->type === 'link')
            <div class="media-placeholder">🔗</div>
        @else
            <div class="media-placeholder">
                @switch($memory->type)
                    @case('text') 📝 @break
                    @default ✨
                @endswitch
            </div>
        @endif
    </div>

    <div class="detail-body">
        <div class="detail-type">{{ $memory->type }}</div>
        <h1 class="detail-title">{{ $memory->title }}</h1>

        @if($memory->description)
            <div class="detail-desc">{{ $memory->description }}</div>
        @endif

        @if($memory->external_link)
            <a href="{{ $memory->external_link }}" target="_blank" rel="noopener" class="detail-link">
                🔗 {{ $memory->external_link }}
            </a>
        @endif

        <div class="detail-meta">
            <span>📅 {{ $memory->created_at->format('d M Y, H:i') }}</span>
            <span>⏱️ {{ $memory->created_at->diffForHumans() }}</span>
            @if($memory->updated_at != $memory->created_at)
                <span>✏️ Diedit {{ $memory->updated_at->diffForHumans() }}</span>
            @endif
        </div>

        <div class="detail-actions">
            <a href="/memory/{{ $memory->id }}/edit" class="btn btn-outline">✏️ Edit</a>
            <form method="POST" action="/memory/{{ $memory->id }}/delete" onsubmit="return confirm('Hapus kenangan ini?')">
                @csrf
                <button type="submit" class="btn btn-danger">🗑️ Hapus</button>
            </form>
        </div>
    </div>
</div>
<style>
    .back-link { display:inline-flex; align-items:center; gap:.5rem; color:#6b7280; text-decoration:none; font-size:.85rem; margin-bottom:1.5rem; transition:color .2s; }
    .back-link:hover { color:#a78bfa; }

    .detail-grid { display:grid; grid-template-columns:1fr 320px; gap:1.5rem; }
    @media(max-width:700px) { .detail-grid { grid-template-columns:1fr; } }

    .detail-card { background:rgba(25,15,45,.9); border:1px solid rgba(150,100,200,.2); border-radius:20px; overflow:hidden; }
    .detail-media { width:100%; background:rgba(15,10,30,.8); }
    .detail-media img { width:100%; max-height:400px; object-fit:contain; display:block; }
    .detail-media video,.detail-media audio { width:100%; display:block; }
    .media-placeholder { padding:3rem; text-align:center; font-size:4rem; }

    .detail-body { padding:1.8rem; }
    .detail-type { font-size:.75rem; color:#7c3aed; text-transform:uppercase; letter-spacing:1px; font-weight:600; margin-bottom:.5rem; }
    .detail-title { font-size:1.8rem; font-weight:700; color:#e8e0f0; margin-bottom:1rem; line-height:1.3; }
    .detail-desc { color:#9ca3af; line-height:1.8; font-size:.95rem; white-space:pre-wrap; margin-bottom:1.5rem; }
    .detail-link { display:inline-flex; align-items:center; gap:.5rem; color:#818cf8; text-decoration:none; background:rgba(99,102,241,.1); border:1px solid rgba(99,102,241,.2); padding:.6rem 1rem; border-radius:10px; font-size:.85rem; margin-bottom:1.5rem; word-break:break-all; }

    .detail-meta { display:flex; gap:1rem; flex-wrap:wrap; padding-top:1rem; border-top:1px solid rgba(150,100,200,.1); font-size:.8rem; color:#4b5563; }

    .detail-actions { display:flex; gap:.8rem; margin-top:1.5rem; padding-top:1.5rem; border-top:1px solid rgba(150,100,200,.1); flex-wrap:wrap; }

    .btn { padding:.5rem 1.2rem; border:none; border-radius:20px; cursor:pointer; font-size:.85rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:.4rem; transition:all .2s; }
    .btn-primary { background:linear-gradient(135deg,#7c3aed,#db2777); color:white; }
    .btn-danger { background:rgba(239,68,68,.15); color:#f87171; border:1px solid rgba(239,68,68,.3); }
    .btn-danger:hover { background:rgba(239,68,68,.3); }
    .btn-outline { background:transparent; color:#a78bfa; border:1px solid rgba(167,139,250,.4); }
    .btn-outline:hover { background:rgba(167,139,250,.1); }

    /* SIDEBAR */
    .sidebar-card { background:rgba(25,15,45,.9); border:1px solid rgba(150,100,200,.2); border-radius:16px; padding:1.2rem; margin-bottom:1rem; }
    .sidebar-title { font-size:.8rem; color:#6b7280; text-transform:uppercase; letter-spacing:1px; font-weight:600; margin-bottom:1rem; }

    .qr-container { text-align:center; }
    .qr-container img { width:160px; height:160px; border-radius:12px; background:white; padding:8px; display:block; margin:0 auto .8rem; }
    .qr-url { font-size:.72rem; color:#4b5563; word-break:break-all; margin-bottom:.8rem; }

    .stats-grid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:.8rem; }
    .stat-box { background:rgba(15,10,30,.6); border-radius:10px; padding:.7rem; text-align:center; }
    .stat-num { font-size:1.4rem; font-weight:700; color:#c084fc; }
    .stat-label { font-size:.7rem; color:#4b5563; margin-top:.2rem; }

    .request-item { display:flex; align-items:center; justify-content:space-between; padding:.6rem; background:rgba(15,10,30,.4); border-radius:8px; margin-bottom:.5rem; font-size:.82rem; }
    .req-name { color:#e8e0f0; font-weight:500; }
    .req-time { color:#4b5563; font-size:.72rem; }
    .req-actions { display:flex; gap:.4rem; }
    .btn-xs { padding:.25rem .7rem; font-size:.72rem; border-radius:12px; border:none; cursor:pointer; font-weight:600; }
    .btn-approve { background:rgba(16,185,129,.2); color:#6ee7b7; }
    .btn-approve:hover { background:rgba(16,185,129,.35); }
    .btn-reject { background:rgba(239,68,68,.15); color:#f87171; }
    .btn-reject:hover { background:rgba(239,68,68,.3); }

    .see-all { display:block; text-align:center; color:#7c3aed; font-size:.78rem; margin-top:.5rem; text-decoration:none; }
    .see-all:hover { color:#c084fc; }

    .public-link { display:flex; align-items:center; gap:.5rem; background:rgba(124,58,237,.1); border:1px dashed rgba(124,58,237,.3); border-radius:10px; padding:.6rem .8rem; font-size:.78rem; color:#a78bfa; word-break:break-all; margin-top:.5rem; }
</style>

<a href="/home" class="back-link">← Kembali</a>

<div class="detail-grid">
    <!-- MAIN CONTENT -->
    <div>
        <div class="detail-card">
            <div class="detail-media">
                @if($memory->type === 'image' && $memory->file_path)
                    <img src="{{ asset('storage/'.$memory->file_path) }}" alt="{{ $memory->title }}">
                @elseif($memory->type === 'video' && $memory->file_path)
                    <video controls><source src="{{ asset('storage/'.$memory->file_path) }}"></video>
                @elseif($memory->type === 'music' && $memory->file_path)
                    <div class="media-placeholder">🎵</div>
                    <audio controls style="width:100%;padding:1rem"><source src="{{ asset('storage/'.$memory->file_path) }}"></audio>
                @else
                    <div class="media-placeholder">
                        @switch($memory->type)
                            @case('link') 🔗 @break
                            @case('text') 📝 @break
                            @default ✨
                        @endswitch
                    </div>
                @endif
            </div>

            <div class="detail-body">
                <div class="detail-type">{{ $memory->type }}</div>
                <h1 class="detail-title">{{ $memory->title }}</h1>

                @if($memory->description)
                    <div class="detail-desc">{{ $memory->description }}</div>
                @endif

                @if($memory->external_link)
                    <a href="{{ $memory->external_link }}" target="_blank" class="detail-link">
                        🔗 {{ $memory->external_link }}
                    </a>
                @endif

                <div class="detail-meta">
                    <span>📅 {{ $memory->created_at->format('d M Y, H:i') }}</span>
                    <span>⏱️ {{ $memory->created_at->diffForHumans() }}</span>
                    @if($memory->last_accessed_at)
                        <span>👁️ Terakhir dilihat {{ $memory->last_accessed_at->diffForHumans() }}</span>
                    @endif
                </div>

                <div class="detail-actions">
                    <a href="/memory/{{ $memory->id }}/edit" class="btn btn-outline">✏️ Edit</a>
                    <form method="POST" action="/memory/{{ $memory->id }}/delete" onsubmit="return confirm('Hapus kenangan ini?')">
                        @csrf
                        <button type="submit" class="btn btn-danger">🗑️ Hapus</button>
                    </form>
                </div>
                @php
    $comments = $memory->comments()->latest()->get();
    $likeCount = $memory->likes()->count();
@endphp

@if($likeCount > 0 || $comments->isNotEmpty())
<div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid rgba(150,100,200,.1)">

    {{-- LIKES --}}
    @if($likeCount > 0)
    <div style="margin-bottom:1.5rem">
        <div style="font-size:.78rem;color:#6b7280;text-transform:uppercase;letter-spacing:1px;font-weight:600;margin-bottom:.5rem">❤️ Disukai</div>
        <div style="font-size:1.1rem;color:#f87171;font-weight:600">{{ $likeCount }} orang menyukai kenangan ini</div>
    </div>
    @endif

    {{-- KOMENTAR --}}
    @if($comments->isNotEmpty())
    <div>
        <div style="font-size:.78rem;color:#6b7280;text-transform:uppercase;letter-spacing:1px;font-weight:600;margin-bottom:1rem">💬 Komentar ({{ $comments->count() }})</div>
        <div style="display:flex;flex-direction:column;gap:.8rem">
            @foreach($comments as $comment)
            <div style="background:rgba(15,10,30,.5);border-radius:12px;padding:1rem;border:1px solid rgba(150,100,200,.1)">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.4rem">
                    <span style="font-weight:600;font-size:.88rem;color:#c084fc">{{ $comment->guest_name }}</span>
                    <span style="font-size:.72rem;color:#4b5563">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <div style="font-size:.88rem;color:#9ca3af;line-height:1.6">{{ $comment->comment }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endif
            </div>
        </div>
    </div>

    <!-- SIDEBAR -->
    <div>
        <!-- QR Code -->
        <div class="sidebar-card">
            <div class="sidebar-title">🔳 QR Code</div>
            @if($memory->qr_path)
                <div class="qr-container">
                    <img src="{{ asset('storage/'.$memory->qr_path) }}" alt="QR Code">
                    <div class="qr-url">{{ url('/m/'.$memory->slug) }}</div>
                    <a href="{{ asset('storage/'.$memory->qr_path) }}" download="qr-{{ $memory->slug }}.svg" class="btn btn-outline" style="font-size:.8rem;width:100%;justify-content:center">⬇️ Download QR</a>
                </div>
            @else
                <p style="color:#6b7280;font-size:.85rem">QR belum dibuat.</p>
            @endif
            <div class="public-link">🔗 {{ url('/m/'.$memory->slug) }}</div>
        </div>

        <!-- Stats -->
        <div class="sidebar-card">
            <div class="sidebar-title">📊 Statistik</div>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-num">{{ $memory->likes_count }}</div>
                    <div class="stat-label">❤️ Like</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num">{{ $memory->comments_count }}</div>
                    <div class="stat-label">💬 Komen</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num">{{ $memory->view_count }}</div>
                    <div class="stat-label">👁️ Lihat</div>
                </div>
            </div>
        </div>

        <!-- Pending Requests -->
        @if($recentRequests->isNotEmpty())
        <div class="sidebar-card">
            <div class="sidebar-title">🔔 Permintaan Masuk</div>
            @foreach($recentRequests as $req)
            <div class="request-item">
                <div>
                    <div class="req-name">{{ $req->guest_name ?? 'Seseorang' }}</div>
                    <div class="req-time">{{ $req->created_at->diffForHumans() }}</div>
                </div>
                <div class="req-actions">
                    <form method="POST" action="{{ route('access.approve', $req->id) }}">
                        @csrf
                        <button class="btn-xs btn-approve">✓</button>
                    </form>
                    <form method="POST" action="{{ route('access.reject', $req->id) }}">
                        @csrf
                        <button class="btn-xs btn-reject">✕</button>
                    </form>
                </div>
            </div>
            @endforeach
            <a href="{{ route('access.dashboard') }}" class="see-all">Lihat semua →</a>
        </div>
        @endif
    </div>
</div>
@endsection