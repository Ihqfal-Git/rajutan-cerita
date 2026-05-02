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

    input[type=text], input[type=url], textarea {
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

    input:focus, textarea:focus {
        border-color: rgba(192, 132, 252, 0.6);
    }

    textarea { resize: vertical; min-height: 100px; }

    .type-selector {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.5rem;
    }

    .type-option { display: none; }

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
        width: 100%;
    }

    .current-file {
        background: rgba(124, 58, 237, 0.1);
        border: 1px solid rgba(124, 58, 237, 0.2);
        border-radius: 8px;
        padding: 0.6rem 0.8rem;
        font-size: 0.8rem;
        color: #a78bfa;
        margin-bottom: 0.5rem;
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
    <h1 class="form-title">✏️ Edit Kenangan</h1>

    <form method="POST" action="/memory/{{ $memory->id }}/update" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Judul *</label>
            <input type="text" name="title" value="{{ old('title', $memory->title) }}" required>
            @error('title') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description">{{ old('description', $memory->description) }}</textarea>
        </div>

        <div class="form-group">
            <label>Jenis Kenangan</label>
            <div class="type-selector">
                @foreach(['image' => ['🖼️','Foto'], 'video' => ['🎬','Video'], 'music' => ['🎵','Musik'], 'link' => ['🔗','Link'], 'text' => ['📝','Teks']] as $val => $info)
                <div>
                    <input type="radio" name="type" id="type_{{ $val }}" value="{{ $val }}" class="type-option" {{ old('type', $memory->type) === $val ? 'checked' : '' }}>
                    <label for="type_{{ $val }}" class="type-label">
                        <span class="type-emoji">{{ $info[0] }}</span>
                        {{ $info[1] }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label>Upload File Baru (opsional)</label>
            @if($memory->file_path)
                <div class="current-file">📎 File saat ini: {{ basename($memory->file_path) }}</div>
            @endif
            <input type="file" name="file" accept="image/*,video/*,audio/*">
            <small style="color:#4b5563;font-size:0.78rem;margin-top:0.3rem;display:block">Kosongkan jika tidak ingin mengubah file</small>
        </div>

        <div class="form-group">
            <label>Link External</label>
            <input type="url" name="external_link" value="{{ old('external_link', $memory->external_link) }}" placeholder="https://...">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            <a href="/memory/{{ $memory->id }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection