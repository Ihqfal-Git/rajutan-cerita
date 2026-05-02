<?php

namespace App\Http\Controllers;

use App\Models\Memory;
use App\Models\AccessRequest;
use App\Notifications\AccessRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccessRequestController extends Controller
{
    private function getOrCreateGuestToken(): string
    {
        if (!session()->has('guest_token')) {
            session(['guest_token' => (string) Str::uuid()]);
        }
        return session('guest_token');
    }

    public function request(Request $request, string $slug)
    {
        $request->validate([
            'guest_name' => 'required|string|max:100',
        ]);

        $memory     = Memory::where('slug', $slug)->firstOrFail();
        $guestToken = $this->getOrCreateGuestToken();

        $existing = AccessRequest::where('memory_id', $memory->id)
            ->where('guest_token', $guestToken)
            ->first();

        if ($existing) {
            return back()->with('info', 'Permintaan akses sudah terkirim sebelumnya.');
        }

        $accessRequest = AccessRequest::create([
            'memory_id'   => $memory->id,
            'guest_token' => $guestToken,
            'guest_name'  => $request->guest_name,
            'status'      => 'pending',
        ]);

        // Kirim notifikasi ke owner
        $memory->user->notify(new AccessRequestNotification($accessRequest));

        return back()->with('success', 'Permintaan akses berhasil dikirim! Tunggu persetujuan pemilik kenangan.');
    }

    public function approve($id)
    {
        $accessRequest = AccessRequest::findOrFail($id);

        abort_if($accessRequest->memory->user_id !== auth()->id(), 403);

        $accessRequest->update(['status' => 'approved']);

        return back()->with('success', 'Akses telah disetujui ✅');
    }

    public function reject($id)
    {
        $accessRequest = AccessRequest::findOrFail($id);

        abort_if($accessRequest->memory->user_id !== auth()->id(), 403);

        $accessRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Permintaan telah ditolak.');
    }

    public function dashboard()
    {
        $requests = AccessRequest::whereHas('memory', function ($q) {
            $q->where('user_id', auth()->id());
        })
        ->with('memory')
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('dashboard.requests', compact('requests'));
    }
}