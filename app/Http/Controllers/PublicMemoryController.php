<?php

namespace App\Http\Controllers;

use App\Models\Memory;
use App\Models\AccessRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicMemoryController extends Controller
{
    private function getOrCreateGuestToken(): string
    {
        if (!session()->has('guest_token')) {
            session(['guest_token' => (string) Str::uuid()]);
        }
        return session('guest_token');
    }

    public function show(string $slug)
    {
        $memory = Memory::where('slug', $slug)->with('memoryFiles')->firstOrFail();
        $guestToken = $this->getOrCreateGuestToken();

        // Owner sendiri — langsung akses
        if (auth()->check() && auth()->id() === $memory->user_id) {
            return $this->renderMemory($memory, $guestToken, isOwner: true);
        }

        $accessRequest = AccessRequest::where('memory_id', $memory->id)
            ->where('guest_token', $guestToken)
            ->first();

        if (!$accessRequest || $accessRequest->status !== 'approved') {
            return view('memory.request_access', compact('memory', 'accessRequest'));
        }

        // Hitung view
        $memory->increment('view_count');
        $memory->update(['last_accessed_at' => now()]);

        return $this->renderMemory($memory, $guestToken, isOwner: false);
    }

    private function renderMemory(Memory $memory, string $guestToken, bool $isOwner)
    {
        $hasLiked  = $memory->likes()->where('guest_token', $guestToken)->exists();
        $likeCount = $memory->likes()->count();
        $comments  = $memory->comments()->latest()->get();

        return view('memory.public_show', compact('memory', 'hasLiked', 'likeCount', 'comments', 'guestToken', 'isOwner'));
    }
}