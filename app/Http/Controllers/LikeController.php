<?php

namespace App\Http\Controllers;

use App\Models\Memory;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LikeController extends Controller
{
    public function toggle(string $slug)
    {
        $memory = Memory::where('slug', $slug)->firstOrFail();

        if (!session()->has('guest_token')) {
            session(['guest_token' => (string) Str::uuid()]);
        }
        $guestToken = session('guest_token');

        // Pastikan akses sudah diapprove
        $hasAccess = auth()->check() && auth()->id() === $memory->user_id;
        if (!$hasAccess) {
            $approved = $memory->accessRequests()
                ->where('guest_token', $guestToken)
                ->where('status', 'approved')
                ->exists();
            abort_if(!$approved, 403);
        }

        $existing = Like::where('memory_id', $memory->id)
            ->where('guest_token', $guestToken)
            ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            Like::create(['memory_id' => $memory->id, 'guest_token' => $guestToken]);
            $liked = true;
        }

        $count = $memory->likes()->count();

        return response()->json(['liked' => $liked, 'count' => $count]);
    }
}