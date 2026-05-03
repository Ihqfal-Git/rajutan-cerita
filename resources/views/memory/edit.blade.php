@extends('layouts.app')

@section('content')
<style>
    .form-card { background:rgba(25,15,45,.9); border:1px solid rgba(150,100,200,.2); border-radius:20px; padding:2rem; max-width:680px; margin:0 auto; }
    .form-title { font-size:1.5rem; font-weight:700; background:linear-gradient(135deg,#c084fc,#f472b6); -webkit-background-clip:text; -webkit-text-fill-color:transparent; margin-bottom:1.5rem; }
    .form-group { margin-bottom:1.2rem; }
    label { display:block; font-size:.85rem; color:#9ca3af; margin-bottom:.4rem; font-weight:500; }
    input[type=text],input[type=url],textarea { width:100%; background:rgba(15,10,30,.8); border:1px solid rgba(150,100,200,.25); border-radius:10px; color:#e8e0f0; padding:.75rem 1rem; font-size:.9rem; outline:none; font-family:inherit; }
    input:focus,textarea:focus { border-color:rgba(192,132,252,.6); }
    textarea { resize:vertical; min-height:100px; }
    .btn { padding:.5rem 1.2rem; border:none; border-radius:20px; cursor:pointer; font-size:.85rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:.4rem; transition:all .2s; }
    .btn-primary { background:linear-gradient(135deg,#7c3aed,#db2777); color:white; }
    .btn-primary:hover { transform:translateY(-1px); box-shadow:0 4px 20px rgba(124,58,237,.4); }
    .btn-outline { background:transparent; color:#a78bfa; border:1px solid rgba(167,139,250,.4); }
    .btn-outline:hover { background:rgba(167,139,250,.1); }
    .section-divider { border:none; border-top:1px solid rgba(150,100,200,.15); margin:1.5rem 0; }
    .section-label { font-size:.78rem; color:#7c3aed; text-transform:uppercase; letter-spacing:1px; font-weight:600; margin-bottom:1rem; }

    /* EXISTING FILES */
    .existing-files { display:grid; grid-template-columns:repeat(auto-fill,minmax(140px,1fr)); gap:.8rem; margin-bottom:1rem; }
    .existing-file { background:rgba(15,10,30,.7); border:1px solid rgba(150,100,200,.2); border-radius:10px; overflow:hidden; position:relative; }
    .existing-thumb { width:100%; height:90px; object-fit:cover; display:block; }
    .existing-type-icon { width:100%; height:90px; display:flex; align-items:center; justify-content:center; font-size:2rem; }
    .existing-caption { padding:.4rem .5rem; font-size:.72rem; color:#9ca3af; }
    .delete-check { position:absolute; top:.4rem; right:.4rem; }
    .delete-check input { accent-color:#ef4444; width:16px; height:16px; cursor:pointer; }
    .delete-label { font-size:.65rem; color:#f87171; display:block; text-align:center; margin-top:.2rem; }

    .upload-slot { background:rgba(15,10,30,.6); border:1px dashed rgba(150,100,200,.3); border-radius:12px; padding:1rem; margin-bottom:.8rem; }
    .slot-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:.6rem; }
    .slot-num { font-size:.75rem; color:#6b7280; font-weight:600; }
    .slot-remove { background:none; border:none; color:#4b5563; cursor:pointer; font-size:.8rem; padding:.2rem .5rem; border-radius:6px; }
    .slot-remove:hover { color:#f87171; }
    input[type=file] { width:100%; background:transparent; border:none; color:#9ca3af; padding:.3rem 0; font-size:.85rem; cursor:pointer; }
    .caption-input { width:100%; background:rgba(15,10,30,.8); border:1px solid rgba(150,100,200,.2); border-radius:8px; color:#e8e0f0; padding:.5rem .8rem; font-size:.82rem; outline:none; font-family:inherit; margin-top:.5rem; }
    .caption-suggestions { display:flex; flex-wrap:wrap; gap:.4rem; margin-top:.5rem; }
    .cap-chip { background:rgba(124,58,237,.12); border:1px solid rgba(124,58,237,.25); color:#c084fc; font-size:.72rem; padding:.25rem .7rem; border-radius:12px; cursor:pointer; }
    .cap-chip:hover { background:rgba(124,58,237,.25); }
    .add-slot-btn { width:100%; background:rgba(124,58,237,.08); border:1px dashed rgba(124,58,237,.25); color:#7c3aed; padding:.7rem; border-radius:10px; cursor:pointer; font-size:.85rem; font-weight:600; transition:all .2s; }
    .add-slot-btn:hover { background:rgba(124,58,237,.18); }

    .link-slot { background:rgba(15,10,30,.6); border:1px solid rgba(59,130,246,.2); border-radius:12px; padding:1rem; margin-bottom:.8rem; }
    .counter-bar { background:rgba(15,10,30,.5); border-radius:8px; padding:.5rem .8rem; font-size:.78rem; color:#6b7280; margin-bottom:1rem; }
    .counter-fill { color:#c084fc; font-weight:700; }
</style>

<div class="form-card">
    <h1 class="form-title">✏️ Edit Kenangan</h1>

    <form method="POST" action="/memory/{{ $memory->id }}/update" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Judul *</label>
            <input type="text" name="title" value="{{ old('title', $memory->title) }}" required>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description">{{ old('description', $memory->description) }}</textarea>
        </div>

        <hr class="section-divider">

        {{-- FILE YANG SUDAH ADA --}}
        @if($memory->memoryFiles->isNotEmpty())
        <div class="form-group">
            <div class="section-label">📂 File Tersimpan ({{ $memory->memoryFiles->count() }}/5)</div>
            <p style="font-size:.78rem;color:#6b7280;margin-bottom:.8rem">Centang untuk menghapus file tersebut</p>
            <div class="existing-files">
                @foreach($memory->memoryFiles as $mf)
                <div class="existing-file">
                    @if($mf->file_type === 'image')
                        <img src="{{ asset('storage/'.$mf->file_path) }}" class="existing-thumb" alt="">
                    @elseif($mf->file_type === 'youtube')
                        <div class="existing-type-icon" style="background:rgba(239,68,68,.1)">▶️</div>
                    @elseif($mf->file_type === 'spotify')
                        <div class="existing-type-icon" style="background:rgba(16,185,129,.1)">🎵</div>
                    @elseif($mf->file_type === 'music')
                        <div class="existing-type-icon" style="background:rgba(124,58,237,.1)">🎵</div>
                    @elseif($mf->file_type === 'video')
                        <div class="existing-type-icon" style="background:rgba(99,102,241,.1)">🎬</div>
                    @else
                        <div class="existing-type-icon" style="background:rgba(59,130,246,.1)">🔗</div>
                    @endif
                    <div class="existing-caption">{{ $mf->caption ?: $mf->file_type }}</div>
                    <div class="delete-check">
                        <input type="checkbox" name="delete_files[]" value="{{ $mf->id }}" id="del_{{ $mf->id }}">
                    </div>
                    <label for="del_{{ $mf->id }}" class="delete-label">Hapus</label>
                </div>
                @endforeach
            </div>
        </div>
        <hr class="section-divider">
        @endif

        {{-- TAMBAH FILE BARU --}}
        @php $remaining = 5 - $memory->memoryFiles->count(); @endphp
        @if($remaining > 0)
        <div class="form-group">
            <div class="section-label">➕ Tambah File Baru (sisa: {{ $remainingFiles }} file)</div>
            <div class="counter-bar">Bisa tambah: <span class="counter-fill">{{ $remainingFiles }}</span> file lagi (max 10MB/file)</div>
            <div id="uploadSlots"></div>
            <button type="button" class="add-slot-btn" id="addFileBtn" onclick="addFileSlot()">+ Tambah File</button>
        </div>
        <hr class="section-divider">
        @endif

        {{-- TAMBAH LINK BARU --}}
        @if($remainingLinks > 0)
        <div class="form-group">
            <div class="section-label">🔗 Tambah Link Baru (sisa: {{ $remainingLinks }} link)</div>
            <div class="counter-bar">Bisa tambah: <span class="counter-fill">{{ $remainingLinks }}</span> link lagi</div>
            <div id="linkSlots"></div>
            <button type="button" class="add-slot-btn" id="addLinkBtn" onclick="addLinkSlot()">+ Tambah Link</button>
        </div>
        @endif

        <div style="display:flex;gap:.8rem;margin-top:1.5rem">
            <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            <a href="/memory/{{ $memory->id }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>

<script>
const SUGGESTIONS = @json($suggestions);
const MAX_NEW_FILES = {{ $remainingFiles }};
const MAX_NEW_LINKS = {{ $remainingLinks }};
let fileCount = 0;
let linkCount = 0;

function addFileSlot() {
    if (fileCount >= MAX_NEW_FILES) return;
    const idx = Date.now();
    fileCount++;

    const slot = document.createElement('div');
    slot.className = 'upload-slot';
    slot.id = 'slot_' + idx;
    slot.innerHTML = `
        <div class="slot-header">
            <span class="slot-num">File Baru ${fileCount}</span>
            <button type="button" class="slot-remove" onclick="removeSlot('slot_${idx}');fileCount--">✕ Hapus</button>
        </div>
        <input type="file" name="files[]" accept="image/*,video/*,audio/*" onchange="checkSize(this,'${idx}')">
        <div id="hint_${idx}" style="font-size:.72rem;color:#6b7280;margin-top:.3rem"></div>
        <input type="text" name="captions[]" class="caption-input" placeholder="Keterangan..." id="cap_${idx}">
        <div class="caption-suggestions">
            ${SUGGESTIONS.map(s => `<span class="cap-chip" onclick="document.getElementById('cap_${idx}').value='${s}'">${s}</span>`).join('')}
        </div>
    `;
    document.getElementById('uploadSlots').appendChild(slot);

    const btn = document.getElementById('addFileBtn');
    btn.disabled = fileCount >= MAX_NEW_FILES;
    btn.style.opacity = fileCount >= MAX_NEW_FILES ? '.4' : '1';
}

function addLinkSlot() {
    if (linkCount >= MAX_NEW_LINKS) return;
    const idx = Date.now();
    linkCount++;

    const slot = document.createElement('div');
    slot.className = 'link-slot';
    slot.id = 'lslot_' + idx;
    slot.innerHTML = `
        <div style="display:flex;justify-content:space-between;margin-bottom:.5rem">
            <span style="font-size:.75rem;color:#6b7280;font-weight:600">Link Baru ${linkCount}</span>
            <button type="button" class="slot-remove" onclick="removeSlot('lslot_${idx}');linkCount--">✕ Hapus</button>
        </div>
        <input type="url" name="links[]" placeholder="https://youtube.com/... atau https://open.spotify.com/..."
            style="width:100%;background:rgba(15,10,30,.8);border:1px solid rgba(150,100,200,.25);border-radius:10px;color:#e8e0f0;padding:.65rem .9rem;font-size:.88rem;outline:none;font-family:inherit;margin-bottom:.6rem">
        <input type="text" name="link_captions[]" class="caption-input" placeholder="Keterangan link..." id="lcap_${idx}">
        <div class="caption-suggestions" style="margin-top:.5rem">
            ${SUGGESTIONS.map(s => `<span class="cap-chip" onclick="document.getElementById('lcap_${idx}').value='${s}'">${s}</span>`).join('')}
        </div>
    `;
    document.getElementById('linkSlots').appendChild(slot);

    const btn = document.getElementById('addLinkBtn');
    btn.disabled = linkCount >= MAX_NEW_LINKS;
    btn.style.opacity = linkCount >= MAX_NEW_LINKS ? '.4' : '1';
}

function removeSlot(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

function checkSize(input, idx) {
    const hint = document.getElementById('hint_' + idx);
    if (!input.files[0]) return;
    const size = input.files[0].size;
    const maxSize = 10 * 1024 * 1024;
    if (size > maxSize) {
        hint.textContent = '⚠️ File terlalu besar! Maksimal 10MB';
        hint.style.color = '#f87171';
        input.value = '';
        return;
    }
    const mime = input.files[0].type;
    const label = mime.startsWith('image/') ? '🖼️ Foto' : mime.startsWith('video/') ? '🎬 Video' : '🎵 Musik';
    hint.textContent = label + ' — ' + (size / (1024*1024)).toFixed(1) + ' MB';
    hint.style.color = '#c084fc';
}
</script>
@endsection