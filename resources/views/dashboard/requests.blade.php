@extends('layouts.app')

@section('content')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; flex-wrap:wrap; gap:1rem; }
    .page-title { font-size:1.8rem; font-weight:700; background:linear-gradient(135deg,#c084fc,#f472b6); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }

    .btn { padding:.5rem 1.2rem; border:none; border-radius:20px; cursor:pointer; font-size:.85rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:.4rem; transition:all .2s; }
    .btn-outline { background:transparent; color:#a78bfa; border:1px solid rgba(167,139,250,.4); }
    .btn-outline:hover { background:rgba(167,139,250,.1); }

    .filter-tabs { display:flex; gap:.5rem; margin-bottom:1.5rem; flex-wrap:wrap; }
    .filter-tab { padding:.4rem 1rem; border-radius:20px; font-size:.82rem; font-weight:600; text-decoration:none; border:1px solid rgba(150,100,200,.2); color:#6b7280; transition:all .2s; }
    .filter-tab.active,.filter-tab:hover { background:rgba(124,58,237,.2); border-color:rgba(124,58,237,.4); color:#c084fc; }

    .requests-list { display:flex; flex-direction:column; gap:.8rem; }

    .req-card { background:rgba(25,15,45,.9); border:1px solid rgba(150,100,200,.15); border-radius:14px; padding:1.2rem 1.4rem; display:flex; align-items:center; gap:1.2rem; flex-wrap:wrap; }
    .req-card:hover { border-color:rgba(150,100,200,.3); }

    .req-avatar { width:44px; height:44px; border-radius:50%; background:linear-gradient(135deg,#7c3aed,#db2777); display:flex; align-items:center; justify-content:center; font-size:1.2rem; font-weight:700; color:white; flex-shrink:0; }

    .req-info { flex:1; min-width:0; }
    .req-name { font-weight:600; color:#e8e0f0; font-size:.95rem; }
    .req-memory { color:#7c3aed; font-size:.82rem; margin-top:.2rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .req-time { color:#4b5563; font-size:.75rem; margin-top:.2rem; }

    .req-status { padding:.25rem .8rem; border-radius:12px; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; }
    .status-pending  { background:rgba(234,179,8,.15); color:#fbbf24; border:1px solid rgba(234,179,8,.3); }
    .status-approved { background:rgba(16,185,129,.15); color:#6ee7b7; border:1px solid rgba(16,185,129,.3); }
    .status-rejected { background:rgba(239,68,68,.15); color:#f87171; border:1px solid rgba(239,68,68,.3); }

    .req-actions { display:flex; gap:.5rem; }
    .btn-approve { background:rgba(16,185,129,.2); color:#6ee7b7; border:1px solid rgba(16,185,129,.3); padding:.4rem 1rem; border-radius:14px; font-size:.8rem; font-weight:600; cursor:pointer; border:none; transition:all .2s; }
    .btn-approve:hover { background:rgba(16,185,129,.35); }
    .btn-reject  { background:rgba(239,68,68,.1); color:#f87171; border:1px solid rgba(239,68,68,.2); padding:.4rem 1rem; border-radius:14px; font-size:.8rem; font-weight:600; cursor:pointer; border:none; transition:all .2s; }
    .btn-reject:hover { background:rgba(239,68,68,.25); }

    .empty { text-align:center; padding:4rem; color:#4b5563; }
    .empty-icon { font-size:3rem; margin-bottom:.8rem; }

    .pagination-wrap { margin-top:1.5rem; display:flex; justify-content:center; }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">🔔 Permintaan Akses</h1>
        <p style="color:#6b7280;font-size:.9rem;margin-top:.2rem">Kelola siapa yang boleh melihat kenanganmu</p>
    </div>
    <a href="/home" class="btn btn-outline">← Kembali</a>
</div>

@if(session('success'))
    <div style="background:rgba(16,185,129,.15);border:1px solid rgba(16,185,129,.3);color:#6ee7b7;padding:.9rem 1.2rem;border-radius:12px;margin-bottom:1.5rem;font-size:.9rem">
        {{ session('success') }}
    </div>
@endif

@if($requests->isEmpty())
    <div class="empty">
        <div class="empty-icon">📭</div>
        <p>Belum ada permintaan akses masuk.</p>
    </div>
@else
    <div class="requests-list">
        @foreach($requests as $req)
        <div class="req-card">
            <div class="req-avatar">{{ strtoupper(substr($req->guest_name ?? 'G', 0, 1)) }}</div>

            <div class="req-info">
                <div class="req-name">{{ $req->guest_name ?? 'Tamu Anonim' }}</div>
                <div class="req-memory">📦 {{ $req->memory->title }}</div>
                <div class="req-time">⏱️ {{ $req->created_at->diffForHumans() }}</div>
            </div>

            <span class="req-status status-{{ $req->status }}">
                @switch($req->status)
                    @case('pending')  ⏳ Menunggu @break
                    @case('approved') ✅ Disetujui @break
                    @case('rejected') ❌ Ditolak @break
                @endswitch
            </span>

            @if($req->status === 'pending')
            <div class="req-actions">
                <form method="POST" action="{{ route('access.approve', $req->id) }}">
                    @csrf
                    <button type="submit" class="btn-approve">✓ Setujui</button>
                </form>
                <form method="POST" action="{{ route('access.reject', $req->id) }}">
                    @csrf
                    <button type="submit" class="btn-reject">✕ Tolak</button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="pagination-wrap">
        {{ $requests->links() }}
    </div>
@endif
@endsection