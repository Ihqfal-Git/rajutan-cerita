@extends('layouts.app')

@section('content')
<style>
    .form-card {
        background: rgba(25, 15, 45, 0.9);
        border: 1px solid rgba(150, 100, 200, 0.2);
        border-radius: 20px;
        padding: 2rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .form-title {
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #c084fc, #f472b6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1.5rem;
    }

    .form-group { margin-bottom: 1.2rem; }

    label {
        display: block;
        font-size: 0.85rem;
        color: #9ca3af;
        margin-bottom: 0.4rem;
        font-weight: 500;
    }

    input[type=text], input[type=url], textarea, select {
        width: 100%;
        background: rgba(15, 10, 30, 0.8);
        border: 1px solid rgba(150, 100, 200, 0.25);
        border-radius: 10px;
        color: #e8e0f0;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s;
        font-family: inherit;
    }

    input:focus, textarea:focus, select:focus {
        border-color: rgba(192, 132, 252, 0.6);
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }

    textarea { resize: vertical; min-height: 100px; }

    select option { background: #1a0a2e; }

    .type-selector {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.5rem;
    }

    .type-option {
        display: none;
    }

    .type-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.3rem;
        padding: 0.8rem 0.5rem;
        border: 1px solid rgba(150, 100, 200, 0.2);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.75rem;
        color: #6b7280;
        text-align: center;
    }

    .type-label:hover {
        border-color: rgba(192, 132, 252, 0.4);
        color: #a78bfa;
        background: rgba(124, 58, 237, 0.1);
    }

    .type-option:checked + .type-label {
        border-color: #7c3aed;
        background: rgba(124, 58, 237, 0.2);
        color: #c084fc;
    }

    .type-emoji { font-size: 1.5rem; }

    input[type=file] {
        background: rgba(15, 10, 30, 0.8);
        border: 1px dashed rgba(150, 100, 200, 0.3);
        border-radius: 10px;
        color: #9ca3af;
        padding: 1rem;
        cursor: pointer;
    }

    .error-msg {
        color: #f87171;
        font-size: 0.78rem;
        margin-top: 0.3rem;
    }

    .form-actions {
        display: flex;
        gap: 0.8rem;
        margin-top: 1.5rem;
    }
</style>

<div class="form-card">
    <h1 class="form-title">✨ Tambah Kenangan Baru</h1>

    <form method="POST" action="/memory/store" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Judul *</label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="Nama kenangan ini..." required>
            @error('title') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" placeholder="Ceritakan sedikit tentang kenangan ini...">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label>Jenis Kenangan</label>
            <div class="type-selector">
                @foreach(['image' => ['🖼️','Foto'], 'video' => ['🎬','Video'], 'music' => ['🎵','Musik'], 'link' => ['🔗','Link'], 'text' => ['📝','Teks']] as $val => $info)
                <div>
                    <input type="radio" name="type" id="type_{{ $val }}" value="{{ $val }}" class="type-option" {{ old('type', 'text') === $val ? 'checked' : '' }}>
                    <label for="type_{{ $val }}" class="type-label">
                        <span class="type-emoji">{{ $info[0] }}</span>
                        {{ $info[1] }}
                    </label>
                </div>
                @endforeach
            </div>
            @error('type') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Upload File (opsional)</label>
            <input type="file" name="file" accept="image/*,video/*,audio/*">
            <small style="color:#4b5563;font-size:0.78rem;margin-top:0.3rem;display:block">Max 20MB</small>
        </div>

        <div class="form-group">
            <label>Link External (YouTube, Spotify, dll)</label>
            <input type="url" name="external_link" value="{{ old('external_link') }}" placeholder="https://...">
            @error('external_link') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Simpan Kenangan</button>
            <a href="/home" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection