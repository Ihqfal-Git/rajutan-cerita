@extends('layouts.app')

@section('content')
<style>
    .back-link { display:inline-flex; align-items:center; gap:.5rem; color:#6b7280; text-decoration:none; font-size:.85rem; margin-bottom:1.5rem; transition:color .2s; }
    .back-link:hover { color:#a78bfa; }

    .detail-grid { display:grid; grid-template-columns:1fr 300px; gap:1.5rem; align-items:start; }
    @media(max-width:700px) { .detail-grid { grid-template-columns:1fr; } }

    .detail-card { background:rgba(25,15,45,.9); border:1px solid rgba(150,100,200,.2); border-radius:20px; overflow:hidden; }

    .detail-body { padding:1.5rem; }
    .detail-type { font-size:.75rem; color:#7c3aed; text-transform:uppercase; letter-spacing:1px; font-weight:600; margin-bottom:.5rem; }
    .detail-title { font-size:1.6rem; font-weight:700; color:#e8e0f0; margin-bottom:1rem; line-height:1.3; }
    .detail-desc { color:#9ca3af; line-height:1.8; font-size:.95rem; white-space:pre-wrap; margin-bottom:1rem; }

    .detail-meta { display:flex; gap:1rem; flex-wrap:wrap; padding:.8rem 0; border-top:1px solid rgba(150,100,200,.1); font-size:.78rem; color:#4b5563; margin-bottom:1rem; }

    .detail-actions { display:flex; gap:.8rem; flex-wrap:wrap; }

    .btn { padding:.5rem 1.2rem; border:none; border-radius:20px; cursor:pointer; font-size:.85rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:.4rem; transition:all .2s; }
    .btn-outline { background:transparent; color:#a78bfa; border:1px solid rgba(167,139,250,.4); }
    .btn-outline:hover { background:rgba(167,139,250,.1); }
    .btn-danger { background:rgba(239,68,68,.15); color:#f87171; border:1px solid rgba(239,68,68,.3); }
    .btn-danger:hover { background:rgba(239,68,68,.3); }

    /* FILES */
    .files-section { margin-top:1.2rem; padding-top:1.2rem; border-top:1px solid rgba(150,100,200,.1); }
    .files-title { font-size:.75rem; color:#6b7280; text-transform:uppercase; letter-spacing:1px; font-weight:600; margin-bottom:.8rem; }
    .files-list { display:flex; flex-direction:column; gap:1rem; }

    .file-block { background:rgba(15,10,30,.5); border:1px solid rgba(150,100,200,.12); border-radius:12px; overflow:hidden; }
    .player-image { width:100%; max-height:380px; object-fit:contain; display:block; cursor:zoom-in; }
    .player-video { width:100%; display:block; max-height:340px; }
    .player-music { display:flex; flex-direction:column; align-items:center; padding:1.5rem; gap:.8rem; background:linear-gradient(135deg,#1e0a3c,#2d1b69); }
    .music-icon { font-size:2.5rem; }
    .music-name { font-size:.85rem; color:#c084fc; font-weight:500; text-align:center; }
    .music-audio { width:100%; }
    .player-embed { position:relative; padding-bottom:56.25%; height:0; overflow:hidden; }
    .embed-iframe { position:absolute; top:0; left:0; width:100%; height:100%; }
    .spotify-embed { padding-bottom:0 !important; height:152px; }
    .spotify-iframe { position:static !important; width:100%; height:152px; }
    .link-fallback { display:flex; align-items:center; gap:.5rem; padding:1rem; color:#93c5fd; text-decoration:none; word-break:break-all; font-size:.88rem; }
    .link-fallback:hover { color:#c084fc; }
    .file-caption { padding:.5rem 1rem; font-size:.8rem; color:#9ca3af; border-top:1px solid rgba(150,100,200,.08); font-style:italic; }

    /* SIDEBAR */
    .sidebar-card { background:rgba(25,15,45,.9); border:1px solid rgba(150,100,200,.2); border-radius:16px; padding:1.2rem; margin-bottom:1rem; }
    .sidebar-title { font-size:.78rem; color:#6b7280; text-transform:uppercase; letter-spacing:1px; font-weight:600; margin-bottom:1rem; }

    .qr-container { text-align:center; }
    .qr-container img { width:150px; height:150px; border-radius:10px; background:white; padding:8px; display:block; margin:0 auto .7rem; }
    .qr-url { font-size:.7rem; color:#4b5563; word-break:break-all; margin-bottom:.7rem; }

    .stats-grid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:.6rem; }
    .stat-box { background:rgba(15,10,30,.6); border-radius:10px; padding:.7rem; text-align:center; }
    .stat-num { font-size:1.3rem; font-weight:700; color:#c084fc; }
    .stat-label { font-size:.68rem; color:#4b5563; margin-top:.2rem; }

    .request-item { display:flex; align-items:center; justify-content:space-between; padding:.6rem; background:rgba(15,10,30,.4); border-radius:8px; margin-bottom:.5rem; }
    .req-name { color:#e8e0f0; font-weight:500; font-size:.82rem; }
    .req-time { color:#4b5563; font-size:.7rem; }
    .req-actions { display:flex; gap:.3rem; }
    .btn-xs { padding:.25rem .7rem; font-size:.72rem; border-radius:12px; border:none; cursor:pointer; font-weight:600; }
    .btn-approve { background:rgba(16,185,129,.2); color:#6ee7b7; }
    .btn-reject  { background:rgba(239,68,68,.15); color:#f87171; }
    .see-all { display:block; text-align:center; color:#7c3aed; font-size:.78rem; margin-top:.5rem; text-decoration:none; }
    .public-link { display:flex; align-items:center; gap:.5rem; background:rgba(124,58,237,.1); border:1px dashed rgba(124,58,237,.3); border-radius:10px; padding:.6rem .8rem; font-size:.72rem; color:#a78bfa; word-break:break-all; margin-top:.5rem; }

    /* COMMENTS */
    .comments-section { margin-top:1.2rem; padding-top:1.2rem; border-top:1px solid rgba(150,100,200,.1); }
    .comment-item { background:rgba(15,10,30,.5); border-radius:10px; padding:.9rem; margin-bottom:.6rem; border:1px solid rgba(150,100,200,.08); }
    .comment-header { display:flex; justify-content:space-between; margin-bottom:.3rem; }
    .comment-name { font-weight:600; font-size:.85rem; color:#c084fc; }
    .comment-time { font-size:.7rem; color:#4b5563; }
    .comment-text { font-size:.85rem; color:#9ca3af; line-height:1.6; }

    /* LIGHTBOX */
    .lightbox { display:none; position:fixed; top:0;left:0;right:0;bottom:0; background:rgba(0,0,0,.93); z-index:9999; align-items:center; justify-content:center; cursor:zoom-out; }
    .lightbox.active { display:flex; }
    .lightbox img { max-width:95vw; max-height:95vh; object-fit:contain; border-radius:8px; }
    .lightbox-close { position:absolute; top:1rem; right:1.5rem; color:white; font-size:2rem; cursor:pointer; background:none; border:none; }
</style>

<a href="/home" class="back-link">← Kembali</a>

<div class="detail-grid">

    {{-- KOLOM KIRI: KONTEN UTAMA --}}
    <div>
        <div class="detail-card">
            <div class="detail-body">
                <div class="detail-type">{{ $memory->type }}</div>
                <h1 class="detail-title">{{ $memory->title }}</h1>

                @if($memory->description)
                    <div class="detail-desc">{{ $memory->description }}</div>
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

                {{-- FILES --}}
                @if($memory->memoryFiles->isNotEmpty())
                <div class="files-section">
                    <div class="files-title">📎 Konten ({{ $memory->memoryFiles->count() }} file)</div>
                    <div class="files-list">
                        @foreach($memory->memoryFiles as $mf)
                        <div class="file-block">
                            @if($mf->file_type === 'image')
                                <img src="{{ asset('storage/'.$mf->file_path) }}" class="player-image" onclick="openLightbox(this.src)">
                            @elseif($mf->file_type === 'video')
                                <video controls class="player-video" preload="metadata">
                                    <source src="{{ asset('storage/'.$mf->file_path) }}">
                                </video>
                            @elseif($mf->file_type === 'music')
                                <div class="player-music">
                                    <div class="music-icon">🎵</div>
                                    <div class="music-name">{{ $mf->caption ?: basename($mf->file_path) }}</div>
                                    <audio controls class="music-audio" preload="metadata">
                                        <source src="{{ asset('storage/'.$mf->file_path) }}">
                                    </audio>
                                </div>
                            @elseif($mf->file_type === 'youtube')
                                @php $embedUrl = $mf->getYoutubeEmbedUrl(); @endphp
                                @if($embedUrl)
                                    <div class="player-embed">
                                        <iframe src="{{ $embedUrl }}" frameborder="0" allowfullscreen
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            class="embed-iframe"></iframe>
                                    </div>
                                @else
                                    <a href="{{ $mf->file_path }}" target="_blank" class="link-fallback">▶️ Buka di YouTube</a>
                                @endif
                            @elseif($mf->file_type === 'spotify')
                                @php $embedUrl = $mf->getSpotifyEmbedUrl(); @endphp
                                @if($embedUrl)
                                    <div class="spotify-embed">
                                        <iframe src="{{ $embedUrl }}" frameborder="0" allowtransparency="true"
                                            allow="encrypted-media" class="spotify-iframe"></iframe>
                                    </div>
                                @else
                                    <a href="{{ $mf->file_path }}" target="_blank" class="link-fallback">🎵 Buka di Spotify</a>
                                @endif
                            @else
                                <a href="{{ $mf->file_path }}" target="_blank" rel="noopener" class="link-fallback">
                                    🔗 {{ $mf->file_path }}
                                </a>
                            @endif

                            @if($mf->caption)
                                <div class="file-caption">{{ $mf->caption }}</div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- KOMENTAR --}}
                @if($comments->isNotEmpty())
                <div class="comments-section">
                    <div class="files-title">💬 Komentar ({{ $comments->count() }})</div>
                    @foreach($comments as $comment)
                    <div class="comment-item">
                        <div class="comment-header">
                            <span class="comment-name">{{ $comment->guest_name }}</span>
                            <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="comment-text">{{ $comment->comment }}</div>
                    </div>
                    @endforeach
                </div>
                @endif

            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: SIDEBAR --}}
    <div>
        {{-- QR --}}
        <div class="sidebar-card">
            <div class="sidebar-title">🔳 QR Code</div>
            @if($memory->qr_path)
                <div class="qr-container">
                    <img src="{{ asset('storage/'.$memory->qr_path) }}" alt="QR Code">
                    <div class="qr-url">{{ url('/m/'.$memory->slug) }}</div>
                    <a href="{{ asset('storage/'.$memory->qr_path) }}" download="qr-{{ $memory->slug }}.svg"
                        class="btn btn-outline" style="font-size:.78rem;width:100%;justify-content:center">
                        ⬇️ Download QR
                    </a>
                </div>
            @endif
            <div class="public-link">🔗 {{ url('/m/'.$memory->slug) }}</div>
        </div>

        {{-- STATISTIK --}}
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

        {{-- PENDING REQUESTS --}}
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
                        @csrf <button class="btn-xs btn-approve">✓</button>
                    </form>
                    <form method="POST" action="{{ route('access.reject', $req->id) }}">
                        @csrf <button class="btn-xs btn-reject">✕</button>
                    </form>
                </div>
            </div>
            @endforeach
            <a href="{{ route('access.dashboard') }}" class="see-all">Lihat semua →</a>
        </div>
        @endif
    </div>

</div>

{{-- LIGHTBOX --}}
<div class="lightbox" id="lightbox" onclick="closeLightbox()">
    <button class="lightbox-close" onclick="closeLightbox()">✕</button>
    <img src="" id="lightboxImg" alt="">
</div>

<script>
function openLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightbox').classList.add('active');
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
}
document.addEventListener('keydown', e => { if(e.key==='Escape') closeLightbox(); });
</script>

@endsection