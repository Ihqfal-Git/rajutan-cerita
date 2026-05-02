<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $memory->title }} — Rajutan Cerita</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',system-ui,sans-serif; background:#0f0f1a; color:#e8e0f0; min-height:100vh; }

        .top-bar { background:rgba(20,15,35,.95); border-bottom:1px solid rgba(150,100,200,.15); padding:.8rem 1.5rem; display:flex; justify-content:space-between; align-items:center; }
        .brand { font-size:1.1rem; font-weight:700; background:linear-gradient(135deg,#c084fc,#f472b6); -webkit-background-clip:text; -webkit-text-fill-color:transparent; text-decoration:none; }
        .owner-tag { font-size:.78rem; color:#6b7280; }

        .container { max-width:680px; margin:0 auto; padding:2rem 1.5rem; }

        .memory-card { background:rgba(25,15,45,.9); border:1px solid rgba(150,100,200,.2); border-radius:20px; overflow:hidden; margin-bottom:1.5rem; }

        .memory-media img { width:100%; max-height:400px; object-fit:contain; display:block; }
        .memory-media video { width:100%; display:block; }
        .memory-media audio { width:100%; padding:1rem; display:block; }
        .media-placeholder { padding:3rem; text-align:center; font-size:4rem; }

        .memory-body { padding:1.5rem; }
        .memory-type { font-size:.72rem; color:#7c3aed; text-transform:uppercase; letter-spacing:1px; font-weight:600; margin-bottom:.4rem; }
        .memory-title { font-size:1.6rem; font-weight:700; color:#e8e0f0; margin-bottom:1rem; }
        .memory-desc { color:#9ca3af; line-height:1.8; white-space:pre-wrap; margin-bottom:1rem; }
        .memory-link { display:inline-flex; align-items:center; gap:.5rem; color:#818cf8; text-decoration:none; background:rgba(99,102,241,.1); border:1px solid rgba(99,102,241,.2); padding:.5rem .9rem; border-radius:10px; font-size:.85rem; word-break:break-all; }

        .memory-meta { display:flex; gap:1rem; flex-wrap:wrap; padding-top:1rem; border-top:1px solid rgba(150,100,200,.1); font-size:.78rem; color:#4b5563; }

        /* LIKE */
        .like-section { display:flex; align-items:center; gap:1rem; padding:1rem 1.5rem; border-top:1px solid rgba(150,100,200,.1); }
        .like-btn { background:none; border:1px solid rgba(239,68,68,.3); color:#f87171; padding:.5rem 1.2rem; border-radius:20px; cursor:pointer; font-size:.9rem; font-weight:600; transition:all .2s; display:flex; align-items:center; gap:.4rem; }
        .like-btn.liked { background:rgba(239,68,68,.2); border-color:rgba(239,68,68,.5); }
        .like-btn:hover { background:rgba(239,68,68,.2); }
        .like-count { color:#6b7280; font-size:.85rem; }

        /* COMMENTS */
        .comments-section { background:rgba(25,15,45,.9); border:1px solid rgba(150,100,200,.2); border-radius:20px; padding:1.5rem; }
        .section-title { font-size:1rem; font-weight:600; color:#c084fc; margin-bottom:1.2rem; }

        .comment-form { margin-bottom:1.5rem; }
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:.8rem; margin-bottom:.8rem; }
        @media(max-width:480px) { .form-row { grid-template-columns:1fr; } }

        input,textarea { width:100%; background:rgba(15,10,30,.8); border:1px solid rgba(150,100,200,.25); border-radius:10px; color:#e8e0f0; padding:.7rem .9rem; font-size:.88rem; outline:none; font-family:inherit; }
        input:focus,textarea:focus { border-color:rgba(192,132,252,.5); }
        textarea { resize:vertical; min-height:80px; }

        .btn-comment { background:linear-gradient(135deg,#7c3aed,#db2777); color:white; border:none; padding:.65rem 1.5rem; border-radius:20px; font-size:.88rem; font-weight:600; cursor:pointer; transition:all .2s; }
        .btn-comment:hover { transform:translateY(-1px); }

        .comment-list { margin-top:1.2rem; display:flex; flex-direction:column; gap:.8rem; }
        .comment-item { background:rgba(15,10,30,.5); border-radius:12px; padding:1rem; }
        .comment-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:.4rem; }
        .comment-name { font-weight:600; font-size:.88rem; color:#c084fc; }
        .comment-time { font-size:.72rem; color:#4b5563; }
        .comment-text { font-size:.88rem; color:#9ca3af; line-height:1.6; }

        .no-comments { text-align:center; color:#4b5563; font-size:.85rem; padding:1.5rem 0; }

        .alert-success { background:rgba(16,185,129,.15); border:1px solid rgba(16,185,129,.3); color:#6ee7b7; padding:.8rem 1rem; border-radius:10px; margin-bottom:1rem; font-size:.85rem; }
    </style>
</head>
<body>
    <div class="top-bar">
        <a href="/" class="brand">🧶 Rajutan Cerita</a>
        <span class="owner-tag">Kenangan milik {{ $memory->user->name }}</span>
    </div>

    <div class="container">
        @if(session('comment_success'))
            <div class="alert-success">{{ session('comment_success') }}</div>
        @endif

        <!-- MEMORY CARD -->
        <div class="memory-card">
            <div class="memory-media">
                <div class="memory-media">
                    @if($memory->memoryFiles->isNotEmpty())
                        @foreach($memory->memoryFiles as $mf)
                            @include('memory._file_player', ['mf' => $mf])
                        @endforeach
                    @else
                        <div style="padding:3rem;text-align:center;font-size:4rem">✨</div>
                    @endif
                </div>
            </div>

            <div class="memory-body">
                <div class="memory-type">{{ $memory->type }}</div>
                <h1 class="memory-title">{{ $memory->title }}</h1>

                @if($memory->description)
                    <div class="memory-desc">{{ $memory->description }}</div>
                @endif

                @if($memory->external_link)
                    <a href="{{ $memory->external_link }}" target="_blank" class="memory-link">🔗 {{ $memory->external_link }}</a>
                @endif

                <div class="memory-meta">
                    <span>📅 {{ $memory->created_at->format('d M Y') }}</span>
                    <span>⏱️ {{ $memory->created_at->diffForHumans() }}</span>
                </div>
            </div>

            <!-- LIKE -->
            <div class="like-section">
                <button class="like-btn {{ $hasLiked ? 'liked' : '' }}" id="likeBtn" onclick="toggleLike()">
                    ❤️ <span id="likeBtnText">{{ $hasLiked ? 'Disukai' : 'Suka' }}</span>
                </button>
                <span class="like-count" id="likeCount">{{ $likeCount }} suka</span>
            </div>
        </div>

        <!-- COMMENTS -->
        <div class="comments-section">
            <div class="section-title">💬 Komentar ({{ $comments->count() }})</div>

            @if(!$isOwner)
            <div class="comment-form">
                <form method="POST" action="{{ route('memory.comment', $memory->slug) }}">
                    @csrf
                    <div class="form-row">
                        <input type="text" name="guest_name" placeholder="Namamu" required value="{{ old('guest_name') }}">
                    </div>
                    <textarea name="comment" placeholder="Tulis komentar..." required>{{ old('comment') }}</textarea>
                    <br><br>
                    <button type="submit" class="btn-comment">Kirim Komentar</button>
                </form>
            </div>
            @endif

            @if($comments->isEmpty())
                <div class="no-comments">Belum ada komentar. Jadilah yang pertama! 💬</div>
            @else
                <div class="comment-list">
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

    <script>
        let liked = {{ $hasLiked ? 'true' : 'false' }};

        async function toggleLike() {
            const btn = document.getElementById('likeBtn');
            btn.disabled = true;

            try {
                const res = await fetch('{{ route("memory.like", $memory->slug) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                });

                const data = await res.json();
                liked = data.liked;

                btn.className = 'like-btn' + (liked ? ' liked' : '');
                document.getElementById('likeBtnText').textContent = liked ? 'Disukai' : 'Suka';
                document.getElementById('likeCount').textContent = data.count + ' suka';
            } catch(e) {
                console.error(e);
            }

            btn.disabled = false;
        }
    </script>
</body>
</html>