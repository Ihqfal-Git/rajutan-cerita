<?php

namespace App\Http\Controllers;

use App\Models\Memory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MemoryController extends Controller
{
    public function home()
    {
        $memories = Memory::where('user_id', auth()->id())
            ->withCount(['likes', 'comments', 'accessRequests'])
            ->latest()
            ->get();

        $pendingCount = \App\Models\AccessRequest::whereHas('memory', function ($q) {
            $q->where('user_id', auth()->id());
        })->where('status', 'pending')->count();

        return view('memory.home', compact('memories', 'pendingCount'));
    }

    public function create()
    {
        return view('memory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'type'          => 'required|in:image,video,music,link,text',
            'file'          => 'nullable|file|max:20480',
            'external_link' => 'nullable|url',
        ]);

        $filePath = $request->hasFile('file')
            ? $request->file('file')->store('memory/' . auth()->id(), 'public')
            : null;

        // slug unik
        $slug = Str::slug($request->title) . '-' . Str::random(6);
        while (Memory::where('slug', $slug)->exists()) {
            $slug = Str::slug($request->title) . '-' . Str::random(6);
        }

        $memory = Memory::create([
            'user_id'       => auth()->id(),
            'slug'          => $slug,
            'title'         => $request->title,
            'description'   => $request->description,
            'type'          => $request->type,
            'file_path'     => $filePath,
            'external_link' => $request->external_link,
        ]);

        $this->generateQrCode($memory);

        return redirect('/home')->with('success', 'Kenangan berhasil disimpan! ✨');
    }

    public function show($id)
    {
        $memory = Memory::where('id', $id)
            ->where('user_id', auth()->id())
            ->withCount(['likes', 'comments'])
            ->firstOrFail();

        $recentRequests = $memory->accessRequests()
            ->where('status', 'pending')
            ->latest()
            ->take(3)
            ->get();

        return view('memory.show', compact('memory', 'recentRequests'));
    }

    public function edit($id)
    {
        $memory = Memory::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('memory.edit', compact('memory'));
    }

    public function update(Request $request, $id)
    {
        $memory = Memory::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'type'          => 'required|in:image,video,music,link,text',
            'file'          => 'nullable|file|max:20480',
            'external_link' => 'nullable|url',
        ]);

        $filePath = $memory->file_path;

        if ($request->hasFile('file')) {
            if ($filePath) Storage::disk('public')->delete($filePath);
            $filePath = $request->file('file')->store('memory/' . auth()->id(), 'public');
        }

        $memory->update([
            'title'         => $request->title,
            'description'   => $request->description,
            'type'          => $request->type,
            'file_path'     => $filePath,
            'external_link' => $request->external_link,
        ]);

        return redirect('/home')->with('success', 'Kenangan berhasil diperbarui! 🌟');
    }

    public function delete($id)
    {
        $memory = Memory::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($memory->file_path) Storage::disk('public')->delete($memory->file_path);
        if ($memory->qr_path)   Storage::disk('public')->delete($memory->qr_path);

        $memory->delete();

        return redirect('/home')->with('success', 'Kenangan telah dihapus.');
    }

    private function generateQrCode(Memory $memory): void
    {
        $url  = url('/m/' . $memory->slug);
        $path = 'qrcodes/' . $memory->slug . '.svg';

        if (!Storage::disk('public')->exists('qrcodes')) {
            Storage::disk('public')->makeDirectory('qrcodes');
        }

        QrCode::format('svg')
            ->size(300)
            ->generate($url, storage_path('app/public/' . $path));

        $memory->update(['qr_path' => $path]);
    }
}