@extends('layouts.app')

@section('content')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; flex-wrap:wrap; gap:1rem; }
    .page-title { font-size:1.8rem; font-weight:700; background:linear-gradient(135deg,#c084fc,#f472b6); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
    .page-subtitle { color:#6b7280; font-size:0.9rem; margin-top:0.2rem; }
    .header-actions { display:flex; gap:0.8rem; align-items:center; flex-wrap:wrap; }

    .notif-badge { position:relative; display:inline-flex; }
    .badge-dot { position:absolute; top:-4px; right:-4px; background:#ef4444; color:white; font-size:0.65rem; font-weight:700; border-radius:10px; padding:1px 5px; min-width:18px; text-align:center; }

    .btn { padding:.5rem 1.2rem; border:none; border-radius:20px; cursor:pointer; font-size:.85rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:.4rem; transition:all .2s; }
    .btn-primary { background:linear-gradient(135deg,#7c3aed,#db2777); color:white; }
    .btn-primary:hover { transform:translateY(-1px); box-shadow:0 4px 20px rgba(124,58,237,.4); }
    .btn-outline { background:transparent; color:#a78bfa; border:1px solid rgba(167,139,250,.4); }
    .btn-outline:hover { background:rgba(167,139,250,.1); }

    /* GRID */
    .memories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.2rem;
        width: 100%;
    }

    /* CARD */
    .memory-card {
        background: rgba(30,20,50,.8);
        border: 1px solid rgba(150,100,200,.15);
        border-radius: 16px;
        overflow: hidden;
        transition: all .3s;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        position: relative;
        animation: fadeInUp .4s ease both;
        min-width: 0;
    }
    .memory-card:hover { transform:translateY(-4px); border-color:rgba(192,132,252,.4); box-shadow:0 8px 32px rgba(124,58,237,.2); }

    .card-thumb { width:100%; height:150px; object-fit:cover; display:block; }
    .card-type-banner { width:100%; height:150px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:.4rem; font-size:2.5rem; flex-shrink:0; }
    .card-type-label { font-size:.7rem; color:#9ca3af; text-transform:uppercase; letter-spacing:1px; }

    .type-image { background:linear-gradient(135deg,#1e1b4b,#312e81); }
    .type-video { background:linear-gradient(135deg,#1a1a2e,#16213e); }
    .type-music { background:linear-gradient(135deg,#1e0a3c,#2d1b69); }
    .type-link  { background:linear-gradient(135deg,#0a1628,#0f2d4a); }
    .type-text  { background:linear-gradient(135deg,#1a1a1a,#2d2d2d); }

    .card-body { padding:.9rem; flex:1; }
    .card-title { font-weight:600; font-size:.95rem; color:#e8e0f0; margin-bottom:.3rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .card-desc { font-size:.8rem; color:#6b7280; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; line-height:1.5; min-height:2.4em; }

    .card-stats { display:flex; gap:.8rem; padding:.4rem .9rem; font-size:.72rem; color:#4b5563; flex-wrap:wrap; }
    .card-stat { display:flex; align-items:center; gap:.25rem; }

    .card-footer { padding:.5rem .9rem; border-top:1px solid rgba(150,100,200,.1); display:flex; justify-content:space-between; align-items:center; margin-top:auto; }
    .card-date { font-size:.72rem; color:#4b5563; }
    .card-badge { font-size:.65rem; padding:.2rem .55rem; border-radius:10px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; }

    .badge-image { background:rgba(99,102,241,.2); color:#818cf8; }
    .badge-video { background:rgba(239,68,68,.2); color:#f87171; }
    .badge-music { background:rgba(16,185,129,.2); color:#6ee7b7; }
    .badge-link  { background:rgba(59,130,246,.2); color:#93c5fd; }
    .badge-text  { background:rgba(156,163,175,.2); color:#d1d5db; }

    .qr-indicator { position:absolute; top:.5rem; right:.5rem; background:rgba(124,58,237,.85); color:white; font-size:.6rem; font-weight:700; padding:.2rem .5rem; border-radius:6px; }

    .empty-state { text-align:center; padding:5rem 2rem; }
    .empty-icon { font-size:4rem; margin-bottom:1rem; }
    .empty-title { font-size:1.3rem; color:#6b7280; margin-bottom:.5rem; }
    .empty-sub { color:#4b5563; font-size:.9rem; margin-bottom:2rem; }

    @keyframes fadeInUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Kenangan Kamu ✨</h1>
        <p class="page-subtitle">{{ $memories->count() }} kenangan tersimpan</p>
    </div>
    <div class="header-actions">
        <div class="notif-badge">
            <a href="{{ route('access.dashboard') }}" class="btn btn-outline">🔔 Permintaan Akses</a>
            @if($pendingCount > 0)
                <span class="badge-dot">{{ $pendingCount }}</span>
            @endif
        </div>
        <a href="/memory/create" class="btn btn-primary">+ Tambah Kenangan</a>
    </div>
</div>

@if($memories->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">🧶</div>
        <h2 class="empty-title">Belum ada kenangan</h2>
        <p class="empty-sub">Mulai rajut ceritamu hari ini</p>
        <a href="/memory/create" class="btn btn-primary">Buat Kenangan Pertama</a>
    </div>
@else
    <div class="memories-grid">
        @foreach($memories as $i => $memory)
        <a href="/memory/{{ $memory->id }}" class="memory-card" style="animation-delay:{{ $i * .05 }}s">

            @if($memory->qr_path)
                <span class="qr-indicator">QR ✓</span>
            @endif

            {{-- THUMBNAIL --}}
            @php
                $firstFile = $memory->memoryFiles->first();
            @endphp

            @if($firstFile && $firstFile->file_type === 'image')
                <img src="{{ asset('storage/'.$firstFile->file_path) }}" class="card-thumb" alt="{{ $memory->title }}">
            @else
                <div class="card-type-banner type-{{ $memory->type }}">
                    @switch($memory->type)
                        @case('video') 🎬 @break
                        @case('music') 🎵 @break
                        @case('link')  🔗 @break
                        @case('text')  📝 @break
                        @default       🖼️
                    @endswitch
                    <span class="card-type-label">{{ $memory->type }}</span>
                </div>
            @endif

            <div class="card-body">
                <div class="card-title">{{ $memory->title }}</div>
                <div class="card-desc">{{ $memory->description ?? '—' }}</div>
            </div>

            <div class="card-stats">
                <span class="card-stat">❤️ {{ $memory->likes_count }}</span>
                <span class="card-stat">💬 {{ $memory->comments_count }}</span>
                <span class="card-stat">👁️ {{ $memory->view_count }}</span>
                @if($memory->access_requests_count > 0)
                    <span class="card-stat">🔔 {{ $memory->access_requests_count }}</span>
                @endif
            </div>

            <div class="card-footer">
                <span class="card-date">{{ $memory->created_at->diffForHumans() }}</span>
                <span class="card-badge badge-{{ $memory->type }}">{{ $memory->type }}</span>
            </div>
        </a>
        @endforeach
    </div>
@endif
@endsection