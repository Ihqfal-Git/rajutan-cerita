@extends('layouts.app')

@section('content')
<style>
    .form-card { background:rgba(25,15,45,.9); border:1px solid rgba(150,100,200,.2); border-radius:20px; padding:2rem; max-width:680px; margin:0 auto; }
    .form-title { font-size:1.5rem; font-weight:700; background:linear-gradient(135deg,#c084fc,#f472b6); -webkit-background-clip:text; -webkit-text-fill-color:transparent; margin-bottom:1.5rem; }
    .form-group { margin-bottom:1.2rem; }
    label { display:block; font-size:.85rem; color:#9ca3af; margin-bottom:.4rem; font-weight:500; }
    input[type=text],input[type=url],textarea { width:100%; background:rgba(15,10,30,.8); border:1px solid rgba(150,100,200,.25); border-radius:10px; color:#e8e0f0; padding:.75rem 1rem; font-size:.9rem; outline:none; transition:border-color .2s; font-family:inherit; }
    input:focus,textarea:focus { border-color:rgba(192,132,252,.6); }
    textarea { resize:vertical; min-height:100px; }
    .error-msg { color:#f87171; font-size:.78rem; margin-top:.3rem; }
    .form-actions { display:flex; gap:.8rem; margin-top:1.5rem; }
    .btn { padding:.5rem 1.2rem; border:none; border-radius:20px; cursor:pointer; font-size:.85rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:.4rem; transition:all .2s; }
    .btn-primary { background:linear-gradient(135deg,#7c3aed,#db2777); color:white; }
    .btn-primary:hover { transform:translateY(-1px); box-shadow:0 4px 20px rgba(124,58,237,.4); }
    .btn-outline { background:transparent; color:#a78bfa; border:1px solid rgba(167,139,250,.4); }
    .btn-outline:hover { background:rgba(167,139,250,.1); }

    .section-divider { border:none; border-top:1px solid rgba(150,100,200,.15); margin:1.5rem 0; }
    .section-label { font-size:.78rem; color:#7c3aed; text-transform:uppercase; letter-spacing:1px; font-weight:600; margin-bottom:1rem; }

    /* FILE UPLOAD AREA */
    .upload-slots { display:flex; flex-direction:column; gap:.8rem; }
    .upload-slot { background:rgba(15,10,30,.6); border:1px dashed rgba(150,100,200,.3); border-radius:12px; padding:1rem; transition:border-color .2s; }
    .upload-slot:hover { border-color:rgba(192,132,252,.5); }
    .slot-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:.6rem; }
    .slot-num { font-size:.75rem; color:#6b7280; font-weight:600; }
    .slot-remove { background:none; border:none; color:#4b5563; cursor:pointer; font-size:.8rem; padding:.2rem .5rem; border-radius:6px; }
    .slot-remove:hover { color:#f87171; background:rgba(239,68,68,.1); }

    input[type=file] { width:100%; background:transparent; border:none; color:#9ca3af; padding:.3rem 0; font-size:.85rem; cursor:pointer; }

    .caption-area { margin-top:.6rem; }
    .caption-input { width:100%; background:rgba(15,10,30,.8); border:1px solid rgba(150,100,200,.2); border-radius:8px; color:#e8e0f0; padding:.5rem .8rem; font-size:.82rem; outline:none; font-family:inherit; }
    .caption-input:focus { border-color:rgba(192,132,252,.5); }
    .caption-suggestions { display:flex; flex-wrap:wrap; gap:.4rem; margin-top:.5rem; }
    .cap-chip { background:rgba(124,58,237,.12); border:1px solid rgba(124,58,237,.25); color:#c084fc; font-size:.72rem; padding:.25rem .7rem; border-radius:12px; cursor:pointer; transition:all .2s; white-space:nowrap; }
    .cap-chip:hover { background:rgba(124,58,237,.25); }

    .add-slot-btn { width:100%; background:rgba(124,58,237,.08); border:1px dashed rgba(124,58,237,.25); color:#7c3aed; padding:.7rem; border-radius:10px; cursor:pointer; font-size:.85rem; font-weight:600; transition:all .2s; margin-top:.5rem; }
    .add-slot-btn:hover { background:rgba(124,58,237,.18); }

    /* LINK SLOTS */
    .link-slot { background:rgba(15,10,30,.6); border:1px solid rgba(59,130,246,.2); border-radius:12px; padding:1rem; margin-bottom:.8rem; }
    .link-type-badge { display:inline-block; font-size:.68rem; padding:.2rem .6rem; border-radius:8px; font-weight:600; margin-bottom:.5rem; }
    .badge-youtube { background:rgba(239,68,68,.15); color:#f87171; }
    .badge-spotify { background:rgba(16,185,129,.15); color:#6ee7b7; }
    .badge-link    { background:rgba(59,130,246,.15); color:#93c5fd; }

    .counter-bar { background:rgba(15,10,30,.5); border-radius:8px; padding:.5rem .8rem; font-size:.78rem; color:#6b7280; margin-bottom:1rem; display:flex; align-items:center; gap:.5rem; }
    .counter-fill { color:#c084fc; font-weight:700; }
</style>

<div class="form-card">
    <h1 class="form-title">✨ Tambah Kenangan Baru</h1>

    <form method="POST" action="/memory/store" enctype="multipart/form-data" id="memoryForm">
        @csrf

        <div class="form-group">
            <label>Judul *</label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="Nama kenangan ini..." required>
            @error('title') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" placeholder="Ceritakan tentang kenangan ini...">{{ old('description') }}</textarea>
        </div>

        <hr class="section-divider">

        {{-- FILE UPLOADS --}}
        <div class="form-group">
            <div class="section-label">📎 File (Foto / Video / Musik) — Max 5</div>
            <div class="counter-bar">
                <span>Total file:</span>
                <span class="counter-fill" id="fileCounter">0</span>
                <span>/ 5</span>
            </div>

            <div class="upload-slots" id="uploadSlots"></div>
            <button type="button" class="add-slot-btn" id="addFileBtn" onclick="addFileSlot()">
                + Tambah File
            </button>
        </div>

        <hr class="section-divider">

        {{-- LINKS --}}
        <div class="form-group">
            <div class="section-label">🔗 Link (YouTube / Spotify / Lainnya)</div>
            <div id="linkSlots"></div>
            <button type="button" class="add-slot-btn" id="addLinkBtn" onclick="addLinkSlot()">
                + Tambah Link
            </button>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Simpan Kenangan</button>
            <a href="/home" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>

<script>
const SUGGESTIONS = @json($suggestions);
let fileCount = 0;
let linkCount = 0;
const MAX = 5;

function updateCounter() {
    document.getElementById('fileCounter').textContent = fileCount;
    document.getElementById('addFileBtn').disabled = fileCount >= MAX;
    document.getElementById('addFileBtn').style.opacity = fileCount >= MAX ? '.4' : '1';
}

function addFileSlot() {
    if (fileCount >= MAX) return;
    const idx = fileCount;
    fileCount++;

    const slot = document.createElement('div');
    slot.className = 'upload-slot';
    slot.id = 'slot_' + idx;
    slot.innerHTML = `
        <div class="slot-header">
            <span class="slot-num">File ${idx + 1}</span>
            <button type="button" class="slot-remove" onclick="removeFileSlot(${idx})">✕ Hapus</button>
        </div>
        <input type="file" name="files[${idx}]" accept="image/*,video/*,audio/*" onchange="detectFileType(this, ${idx})">
        <div class="file-type-hint" id="hint_${idx}" style="font-size:.72rem;color:#6b7280;margin-top:.3rem"></div>
        <div class="caption-area">
            <input type="text" name="captions[${idx}]" class="caption-input" placeholder="Tambah keterangan (opsional)..." id="cap_${idx}">
            <div class="caption-suggestions">
                ${SUGGESTIONS.map(s => `<span class="cap-chip" onclick="setCap(${idx}, '${s}')">${s}</span>`).join('')}
            </div>
        </div>
    `;
    document.getElementById('uploadSlots').appendChild(slot);
    updateCounter();
}

function removeFileSlot(idx) {
    const el = document.getElementById('slot_' + idx);
    if (el) el.remove();
    fileCount--;
    updateCounter();
}

function detectFileType(input, idx) {
    const hint = document.getElementById('hint_' + idx);
    if (!input.files[0]) return;
    const mime = input.files[0].type;
    if (mime.startsWith('image/'))      hint.textContent = '🖼️ Foto terdeteksi';
    else if (mime.startsWith('video/')) hint.textContent = '🎬 Video terdeteksi';
    else if (mime.startsWith('audio/')) hint.textContent = '🎵 Musik terdeteksi';
    else                                hint.textContent = '📎 File terdeteksi';
    hint.style.color = '#c084fc';
}

function setCap(idx, text) {
    document.getElementById('cap_' + idx).value = text;
}

function addLinkSlot() {
    const idx = linkCount++;
    const slot = document.createElement('div');
    slot.className = 'link-slot';
    slot.id = 'lslot_' + idx;
    slot.innerHTML = `
        <div class="slot-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.5rem">
            <span class="slot-num">Link ${idx + 1}</span>
            <button type="button" class="slot-remove" onclick="removeLinkSlot(${idx})">✕ Hapus</button>
        </div>
        <input type="url" name="links[${idx}]" placeholder="https://youtube.com/... atau https://open.spotify.com/..." style="width:100%;background:rgba(15,10,30,.8);border:1px solid rgba(150,100,200,.25);border-radius:10px;color:#e8e0f0;padding:.65rem .9rem;font-size:.88rem;outline:none;font-family:inherit;margin-bottom:.6rem" oninput="detectLink(this, ${idx})">
        <div id="lhint_${idx}" style="font-size:.72rem;margin-bottom:.5rem"></div>
        <input type="text" name="link_captions[${idx}]" class="caption-input" placeholder="Keterangan link (opsional)..." id="lcap_${idx}">
        <div class="caption-suggestions" style="margin-top:.5rem">
            ${SUGGESTIONS.map(s => `<span class="cap-chip" onclick="setLCap(${idx}, '${s}')">${s}</span>`).join('')}
        </div>
    `;
    document.getElementById('linkSlots').appendChild(slot);
}

function removeLinkSlot(idx) {
    const el = document.getElementById('lslot_' + idx);
    if (el) el.remove();
}

function detectLink(input, idx) {
    const url = input.value;
    const hint = document.getElementById('lhint_' + idx);
    if (url.includes('youtube.com') || url.includes('youtu.be')) {
        hint.textContent = '▶️ YouTube — akan ditampilkan sebagai video embed';
        hint.style.color = '#f87171';
    } else if (url.includes('spotify.com')) {
        hint.textContent = '🎵 Spotify — akan ditampilkan sebagai player embed';
        hint.style.color = '#6ee7b7';
    } else if (url.length > 5) {
        hint.textContent = '🔗 Link biasa — akan ditampilkan sebagai tautan';
        hint.style.color = '#93c5fd';
    } else {
        hint.textContent = '';
    }
}

function setLCap(idx, text) {
    document.getElementById('lcap_' + idx).value = text;
}

// Start dengan 1 slot file
addFileSlot();
</script>
@endsection