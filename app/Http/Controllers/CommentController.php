<?php

namespace App\Http\Controllers;

use App\Models\Memory;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    public function store(Request $request, string $slug)
    {
        $request->validate([
            'guest_name' => 'required|string|max:100',
            'comment'    => 'required|string|max:1000',
        ]);

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

        Comment::create([
            'memory_id'   => $memory->id,
            'guest_token' => $guestToken,
            'guest_name'  => $request->guest_name,
            'comment'     => $request->comment,
        ]);

        return back()->with('comment_success', 'Komentar berhasil dikirim! 💬');
    }
}