<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $memory->title }} — Rajutan Cerita</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',system-ui,sans-serif; background:#0f0f1a; color:#e8e0f0; min-height:100vh; }

        .top-bar { background:rgba(20,15,35,.95); border-bottom:1px solid rgba(150,100,200,.15); padding:.8rem 1.5rem; display:flex; justify-content:space-between; align-items:center; position:sticky; top:0; z-index:50; }
        .brand { font-size:1.1rem; font-weight:700; background:linear-gradient(135deg,#c084fc,#f472b6); -webkit-background-clip:text; -webkit-text-fill-color:transparent; text-decoration:none; }
        .owner-tag { font-size:.78rem; color:#6b7280; }

        .container { max-width:600px; margin:0 auto; padding:1.5rem 1rem 4rem; }

        /* HEADER KENANGAN */
        .memory-header { margin-bottom:1.5rem; }
        .memory-type-badge { display:inline-block; font-size:.7rem; text-transform:uppercase; letter-spacing:1px; font-weight:600; color:#7c3aed; margin-bottom:.4rem; }
        .memory-title { font-size:1.6rem; font-weight:700; color:#e8e0f0; line-height:1.3; margin-bottom:.5rem; }
        .memory-desc { color:#9ca3af; line-height:1.7; font-size:.92rem; white-space:pre-wrap; margin-bottom:.8rem; }
        .memory-meta { display:flex; gap:1rem; flex-wrap:wrap; font-size:.75rem; color:#4b5563; }

        /* FILES */
        .files-list { display:flex; flex-direction:column; gap:1rem; margin-bottom:1.5rem; }

        .file-block { background:rgba(25,15,45,.8); border:1px solid rgba(150,100,200,.15); border-radius:14px; overflow:hidden; }

        /* Image */
        .player-image { width:100%; max-height:400px; object-fit:contain; display:block; cursor:zoom-in; background:#000; }

        /* Video */
        .player-video { width:100%; display:block; max-height:320px; background:#000; }

        /* Music */
        .player-music { background:linear-gradient(135deg,#1e0a3c,#2d1b69); padding:1.5rem; display:flex; flex-direction:column; align-items:center; gap:.8rem; }
        .music-icon { font-size:2.5rem; }
        .music-name { font-size:.85rem; color:#c084fc; font-weight:500; text-align:center; max-width:100%; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .music-audio { width:100%; }

        /* YouTube */
        .youtube-wrap { position:relative; padding-bottom:56.25%; height:0; overflow:hidden; background:#000; }
        .youtube-wrap iframe { position:absolute; top:0; left:0; width:100%; height:100%; border:0; }

        /* Spotify */
        .spotify-wrap { background:#121212; }
        .spotify-wrap iframe { width:100%; height:152px; border:0; display:block; }

        /* Link biasa */
        .link-block { display:flex; align-items:center; gap:.8rem; padding:1rem 1.2rem; text-decoration:none; color:#93c5fd; transition:background .2s; }
        .link-block:hover { background:rgba(59,130,246,.08); }
        .link-icon { font-size:1.3rem; flex-shrink:0; }
        .link-url { font-size:.82rem; word-break:break-all; line-height:1.4; }

        /* Caption */
        .file-caption { padding:.55rem 1rem; font-size:.8rem; color:#a78bfa; border-top:1px solid rgba(150,100,200,.1); font-style:italic; display:flex; align-items:center; gap:.4rem; }
        .file-caption::before { content:'✦'; font-size:.6rem; opacity:.6; }

        /* LIKE */
        .like-bar { display:flex; align-items:center; gap:1rem; background:rgba(25,15,45,.8); border:1px solid rgba(150,100,200,.15); border-radius:14px; padding:1rem 1.2rem; margin-bottom:1.5rem; }
        .like-btn { background:none; border:1px solid rgba(239,68,68,.35); color:#f87171; padding:.5rem 1.2rem; border-radius:20px; cursor:pointer; font-size:.9rem; font-weight:600; transition:all .2s; display:flex; align-items:center; gap:.4rem; }
        .like-btn.liked { background:rgba(239,68,68,.2); border-color:rgba(239,68,68,.6); }
        .like-btn:hover { background:rgba(239,68,68,.15); }
        .like-count { font-size:.85rem; color:#6b7280; }

        /* COMMENTS */
        .comments-card { background:rgba(25,15,45,.8); border:1px solid rgba(150,100,200,.15); border-radius:14px; overflow:hidden; }
        .comments-header { padding:1rem 1.2rem; border-bottom:1px solid rgba(150,100,200,.1); }
        .comments-title { font-size:.9rem; font-weight:600; color:#c084fc; }

        .comment-form { padding:1rem 1.2rem; border-bottom:1px solid rgba(150,100,200,.1); }
        .form-input { width:100%; background:rgba(15,10,30,.8); border:1px solid rgba(150,100,200,.2); border-radius:8px; color:#e8e0f0; padding:.6rem .9rem; font-size:.85rem; outline:none; font-family:inherit; margin-bottom:.6rem; }
        .form-input:focus { border-color:rgba(192,132,252,.5); }
        textarea.form-input { resize:vertical; min-height:75px; }
        .btn-comment { background:linear-gradient(135deg,#7c3aed,#db2777); color:white; border:none; padding:.6rem 1.4rem; border-radius:20px; font-size:.85rem; font-weight:600; cursor:pointer; transition:all .2s; }
        .btn-comment:hover { transform:translateY(-1px); }

        .comment-list { padding:.5rem 0; }
        .comment-item { padding:.9rem 1.2rem; border-bottom:1px solid rgba(150,100,200,.06); }
        .comment-item:last-child { border-bottom:none; }
        .comment-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:.3rem; }
        .comment-name { font-weight:600; font-size:.85rem; color:#c084fc; }
        .comment-time { font-size:.7rem; color:#4b5563; }
        .comment-text { font-size:.85rem; color:#9ca3af; line-height:1.6; }
        .no-comments { padding:1.5rem; text-align:center; color:#4b5563; font-size:.85rem; }

        .alert-success { background:rgba(16,185,129,.15); border:1px solid rgba(16,185,129,.3); color:#6ee7b7; padding:.8rem 1rem; border-radius:10px; margin-bottom:1rem; font-size:.85rem; }

        /* LIGHTBOX */
        .lightbox { display:none; position:fixed; inset:0; background:rgba(0,0,0,.94); z-index:9999; align-items:center; justify-content:center; cursor:zoom-out; }
        .lightbox.active { display:flex; }
        .lightbox img { max-width:95vw; max-height:95vh; object-fit:contain; border-radius:6px; }
        .lb-close { position:absolute; top:1rem; right:1.2rem; color:white; font-size:2rem; cursor:pointer; background:none; border:none; line-height:1; }
    </style>
</head>
<body>

<div class="top-bar">
    <a href="/" class="brand">🧶 Rajutan Cerita</a>
    <span class="owner-tag">milik {{ $memory->user->name }}</span>
</div>

<div class="container">

    @if(session('comment_success'))
        <div class="alert-success">{{ session('comment_success') }}</div>
    @endif

    {{-- OWNER BANNER --}}
    <div style="display:flex;align-items:center;gap:.7rem;background:rgba(124,58,237,.1);border:1px solid rgba(124,58,237,.25);border-radius:12px;padding:.8rem 1rem;margin-bottom:1.2rem">
        <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#db2777);display:flex;align-items:center;justify-content:center;font-size:.9rem;font-weight:700;color:white;flex-shrink:0">
            {{ strtoupper(substr($memory->user->name, 0, 1)) }}
        </div>
        <div>
            <div style="font-size:.7rem;color:#6b7280;margin-bottom:.1rem">Kenangan ini milik</div>
            <div style="font-size:.9rem;font-weight:600;color:#c084fc">{{ $memory->user->name }}</div>
        </div>
        <div style="margin-left:auto;font-size:.7rem;color:#4b5563">🔒 Privat</div>
    </div>

    {{-- HEADER --}}
    <div class="memory-header">
        <div class="memory-type-badge">{{ $memory->type }}</div>
        <h1 class="memory-title">{{ $memory->title }}</h1>
        @if($memory->description)
            <div class="memory-desc">{{ $memory->description }}</div>
        @endif
        <div class="memory-meta">
            <span>📅 {{ $memory->created_at->format('d M Y') }}</span>
            <span>⏱️ {{ $memory->created_at->diffForHumans() }}</span>
        </div>
    </div>

    {{-- FILES --}}
    @if($memory->memoryFiles->isNotEmpty())
    <div class="files-list">
        @foreach($memory->memoryFiles as $mf)
        <div class="file-block">

            @if($mf->file_type === 'image')
                <img src="{{ $mf->url }}"
                     class="player-image" onclick="openLightbox(this.src)" alt="">

            @elseif($mf->file_type === 'video')
                <video controls class="player-video" preload="metadata">
                    <source src="{{ $mf->url }}">
                </video>

            @elseif($mf->file_type === 'music')
                <div class="player-music">
                    <div class="music-icon">🎵</div>
                    <div class="music-name">{{ $mf->caption ?: basename($mf->file_path) }}</div>
                    <audio controls class="music-audio" preload="metadata">
                        <source src="{{ $mf->url }}">
                    </audio>
                </div>

            @elseif($mf->file_type === 'youtube')
                @php $yt = $mf->getYoutubeEmbedUrl(); @endphp
                @if($yt)
                    <div class="youtube-wrap">
                        <iframe src="{{ $yt }}" allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                        </iframe>
                    </div>
                @else
                    <a href="{{ $mf->file_path }}" target="_blank" class="link-block">
                        <span class="link-icon">▶️</span>
                        <span class="link-url">{{ $mf->file_path }}</span>
                    </a>
                @endif

            @elseif($mf->file_type === 'spotify')
                @php $sp = $mf->getSpotifyEmbedUrl(); @endphp
                @if($sp)
                    <div class="spotify-wrap">
                        <iframe src="{{ $sp }}" allowtransparency="true" allow="encrypted-media"></iframe>
                    </div>
                @else
                    <a href="{{ $mf->file_path }}" target="_blank" class="link-block">
                        <span class="link-icon">🎵</span>
                        <span class="link-url">{{ $mf->file_path }}</span>
                    </a>
                @endif

            @else
                <a href="{{ $mf->file_path }}" target="_blank" rel="noopener" class="link-block">
                    <span class="link-icon">🔗</span>
                    <span class="link-url">{{ $mf->file_path }}</span>
                </a>
            @endif

            @if($mf->caption && $mf->file_type !== 'music')
                <div class="file-caption">{{ $mf->caption }}</div>
            @endif

        </div>
        @endforeach
    </div>
    @endif

    {{-- LIKE --}}
    @if(!$isOwner)
    <div class="like-bar">
        <button class="like-btn {{ $hasLiked ? 'liked' : '' }}" id="likeBtn" onclick="toggleLike()">
            ❤️ <span id="likeBtnText">{{ $hasLiked ? 'Disukai' : 'Suka' }}</span>
        </button>
        <span class="like-count" id="likeCount">{{ $likeCount }} suka</span>
    </div>
    @else
    <div class="like-bar">
        <span style="font-size:.85rem;color:#6b7280">❤️ {{ $likeCount }} suka · 💬 {{ $comments->count() }} komentar</span>
    </div>
    @endif

    {{-- COMMENTS --}}
    <div class="comments-card">
        <div class="comments-header">
            <div class="comments-title">💬 Komentar ({{ $comments->count() }})</div>
        </div>

        @if(!$isOwner)
        <div class="comment-form">
            <form method="POST" action="{{ route('memory.comment', $memory->slug) }}">
                @csrf
                <input type="text" name="guest_name" class="form-input"
                       placeholder="Namamu" required value="{{ old('guest_name') }}">
                <textarea name="comment" class="form-input"
                          placeholder="Tulis komentar..." required>{{ old('comment') }}</textarea>
                <button type="submit" class="btn-comment">Kirim 💬</button>
            </form>
        </div>
        @endif

        <div class="comment-list">
            @forelse($comments as $comment)
            <div class="comment-item">
                <div class="comment-header">
                    <span class="comment-name">{{ $comment->guest_name }}</span>
                    <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <div class="comment-text">{{ $comment->comment }}</div>
            </div>
            @empty
            <div class="no-comments">Belum ada komentar. Jadilah yang pertama! 💬</div>
            @endforelse
        </div>
    </div>

</div>

{{-- LIGHTBOX --}}
<div class="lightbox" id="lightbox" onclick="closeLightbox()">
    <button class="lb-close" onclick="closeLightbox()">✕</button>
    <img src="" id="lbImg" alt="">
</div>

<script>
function openLightbox(src) {
    document.getElementById('lbImg').src = src;
    document.getElementById('lightbox').classList.add('active');
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
}
document.addEventListener('keydown', e => { if(e.key==='Escape') closeLightbox(); });

@if(!$isOwner)
async function toggleLike() {
    const btn = document.getElementById('likeBtn');
    btn.disabled = true;
    try {
        const res = await fetch('{{ route("memory.like", $memory->slug) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        });
        const data = await res.json();
        btn.className = 'like-btn' + (data.liked ? ' liked' : '');
        document.getElementById('likeBtnText').textContent = data.liked ? 'Disukai' : 'Suka';
        document.getElementById('likeCount').textContent = data.count + ' suka';
    } catch(e) { console.error(e); }
    btn.disabled = false;
}
@endif
</script>

</body>
</html>