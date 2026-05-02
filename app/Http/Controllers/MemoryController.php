<?php

namespace App\Http\Controllers;

use App\Models\Memory;
use App\Models\MemoryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MemoryController extends Controller
{
    const CAPTION_SUGGESTIONS = [
        'Momen terbaik 💫',
        'Tak terlupakan ✨',
        'Bersama mereka ❤️',
        'Awal segalanya 🌱',
        'Kenangan indah 🌸',
    ];

    public function home()
    {
        $memories = Memory::where('user_id', auth()->id())
            ->withCount(['likes', 'comments', 'accessRequests'])
            ->with(['memoryFiles' => fn($q) => $q->orderBy('order')->limit(1)])
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingCount = \App\Models\AccessRequest::whereHas('memory', function ($q) {
            $q->where('user_id', auth()->id());
        })->where('status', 'pending')->count();

        return view('memory.home', compact('memories', 'pendingCount'));
    }

    public function create()
    {
        return view('memory.create', ['suggestions' => self::CAPTION_SUGGESTIONS]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'nullable|string',
            'files'               => 'nullable|array|max:5',
            'files.*'             => 'nullable|file|max:51200',
            'captions'            => 'nullable|array',
            'captions.*'          => 'nullable|string|max:200',
            'links'               => 'nullable|array|max:5',
            'links.*'             => 'nullable|url',
            'link_captions'       => 'nullable|array',
            'link_captions.*'     => 'nullable|string|max:200',
        ]);

        $slug = Str::slug($request->title) . '-' . Str::random(6);
        while (Memory::where('slug', $slug)->exists()) {
            $slug = Str::slug($request->title) . '-' . Str::random(6);
        }

        // Tentukan type utama dari file pertama
        $type = 'text';
        if ($request->hasFile('files')) {
            $firstMime = $request->file('files')[0]->getMimeType();
            $type = $this->mimeToType($firstMime);
        } elseif (!empty(array_filter($request->input('links', [])))) {
            $type = 'link';
        }

        $memory = Memory::create([
            'user_id'     => auth()->id(),
            'slug'        => $slug,
            'title'       => $request->title,
            'description' => $request->description,
            'type'        => $type,
        ]);

        $order = 0;

        // Simpan file upload
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $i => $file) {
                if (!$file) continue;
                $path     = $file->store('memory/' . auth()->id(), 'public');
                $fileType = $this->mimeToType($file->getMimeType());
                MemoryFile::create([
                    'memory_id' => $memory->id,
                    'file_path' => $path,
                    'file_type' => $fileType,
                    'caption'   => $request->input("captions.{$i}") ?: null,
                    'order'     => $order++,
                ]);
            }
        }

        // Simpan link
        foreach ($request->input('links', []) as $i => $link) {
            if (empty($link)) continue;
            $linkType = $this->detectLinkType($link);
            MemoryFile::create([
                'memory_id' => $memory->id,
                'file_path' => $link,
                'file_type' => $linkType,
                'caption'   => $request->input("link_captions.{$i}") ?: null,
                'order'     => $order++,
            ]);
        }

        $this->generateQrCode($memory);

        return redirect('/home')->with('success', 'Kenangan berhasil disimpan! ✨');
    }

    public function show($id)
    {
        $memory = Memory::where('id', $id)
            ->where('user_id', auth()->id())
            ->withCount(['likes', 'comments'])
            ->with('memoryFiles')
            ->firstOrFail();

        $recentRequests = $memory->accessRequests()
            ->where('status', 'pending')
            ->latest()->take(3)->get();

        $comments = $memory->comments()->latest()->get();

        return view('memory.show', compact('memory', 'recentRequests', 'comments'));
    }

    public function edit($id)
    {
        $memory = Memory::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('memoryFiles')
            ->firstOrFail();

        return view('memory.edit', [
            'memory'      => $memory,
            'suggestions' => self::CAPTION_SUGGESTIONS,
        ]);
    }

    public function update(Request $request, $id)
    {
        $memory = Memory::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('memoryFiles')
            ->firstOrFail();

        $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'files'           => 'nullable|array|max:5',
            'files.*'         => 'nullable|file|max:51200',
            'captions'        => 'nullable|array',
            'captions.*'      => 'nullable|string|max:200',
            'links'           => 'nullable|array|max:5',
            'links.*'         => 'nullable|url',
            'link_captions'   => 'nullable|array',
            'link_captions.*' => 'nullable|string|max:200',
            'delete_files'    => 'nullable|array',
            'delete_files.*'  => 'integer',
        ]);

        $memory->update([
            'title'       => $request->title,
            'description' => $request->description,
        ]);

        // Hapus file yang dicentang untuk dihapus
        if ($request->has('delete_files')) {
            foreach ($request->delete_files as $fileId) {
                $mf = MemoryFile::where('id', $fileId)
                    ->where('memory_id', $memory->id)
                    ->first();
                if ($mf) {
                    if (!in_array($mf->file_type, ['youtube', 'spotify', 'link'])) {
                        Storage::disk('public')->delete($mf->file_path);
                    }
                    $mf->delete();
                }
            }
        }

        $currentCount = $memory->memoryFiles()->count();
        $order        = $memory->memoryFiles()->max('order') + 1;

        // Tambah file baru
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $i => $file) {
                if (!$file || $currentCount >= 5) break;
                $path     = $file->store('memory/' . auth()->id(), 'public');
                $fileType = $this->mimeToType($file->getMimeType());
                MemoryFile::create([
                    'memory_id' => $memory->id,
                    'file_path' => $path,
                    'file_type' => $fileType,
                    'caption'   => $request->input("captions.{$i}") ?: null,
                    'order'     => $order++,
                ]);
                $currentCount++;
            }
        }

        // Tambah link baru
        foreach ($request->input('links', []) as $i => $link) {
            if (empty($link) || $currentCount >= 5) continue;
            $linkType = $this->detectLinkType($link);
            MemoryFile::create([
                'memory_id' => $memory->id,
                'file_path' => $link,
                'file_type' => $linkType,
                'caption'   => $request->input("link_captions.{$i}") ?: null,
                'order'     => $order++,
            ]);
            $currentCount++;
        }

        return redirect('/home')->with('success', 'Kenangan berhasil diperbarui! 🌟');
    }

    public function delete($id)
    {
        $memory = Memory::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('memoryFiles')
            ->firstOrFail();

        foreach ($memory->memoryFiles as $mf) {
            if (!in_array($mf->file_type, ['youtube', 'spotify', 'link'])) {
                Storage::disk('public')->delete($mf->file_path);
            }
        }

        if ($memory->qr_path) Storage::disk('public')->delete($memory->qr_path);

        $memory->delete();

        return redirect('/home')->with('success', 'Kenangan telah dihapus.');
    }

    // ---------- helpers ----------

    private function mimeToType(string $mime): string
    {
        if (str_starts_with($mime, 'image/')) return 'image';
        if (str_starts_with($mime, 'video/')) return 'video';
        if (str_starts_with($mime, 'audio/')) return 'music';
        return 'file';
    }

    private function detectLinkType(string $url): string
    {
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) return 'youtube';
        if (str_contains($url, 'spotify.com')) return 'spotify';
        return 'link';
    }

    private function generateQrCode(Memory $memory): void
    {
        $url = url('/m/' . $memory->slug);
        $dir = storage_path('app/public/qrcodes');
        if (!file_exists($dir)) mkdir($dir, 0755, true);

        $path = 'qrcodes/' . $memory->slug . '.svg';
        QrCode::format('svg')->size(300)->generate($url, storage_path('app/public/' . $path));

        $memory->update(['qr_path' => $path]);
    }
}