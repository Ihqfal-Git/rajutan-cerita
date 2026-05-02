{{-- Dipanggil dengan: @include('memory._file_player', ['mf' => $file]) --}}
@php
    $isLink = in_array($mf->file_type, ['youtube','spotify','link']);
@endphp

<div class="file-block" id="fb_{{ $mf->id }}">
    {{-- PLAYER --}}
    <div class="file-player">
        @if($mf->file_type === 'image')
            <img src="{{ asset('storage/'.$mf->file_path) }}" alt="{{ $mf->caption }}" class="player-image" onclick="openLightbox(this.src)">

        @elseif($mf->file_type === 'video')
            <video controls class="player-video" preload="metadata">
                <source src="{{ asset('storage/'.$mf->file_path) }}">
                Browser tidak mendukung video.
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
                <div class="player-embed spotify-embed">
                    <iframe src="{{ $embedUrl }}" frameborder="0" allowtransparency="true" allow="encrypted-media"
                        class="embed-iframe spotify-iframe"></iframe>
                </div>
            @else
                <a href="{{ $mf->file_path }}" target="_blank" class="link-fallback">🎵 Buka di Spotify</a>
            @endif

        @else
            <a href="{{ $mf->file_path }}" target="_blank" rel="noopener" class="link-fallback">
                🔗 {{ $mf->file_path }}
            </a>
        @endif
    </div>

    {{-- CAPTION --}}
    @if($mf->caption)
        <div class="file-caption">{{ $mf->caption }}</div>
    @endif
</div>