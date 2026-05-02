<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemoryFile extends Model
{
    protected $fillable = [
        'memory_id', 'file_path', 'file_type', 'caption', 'order',
    ];

    public function memory()
    {
        return $this->belongsTo(Memory::class);
    }

    public function getYoutubeEmbedUrl(): ?string
    {
        if ($this->file_type !== 'youtube') return null;

        $url = $this->file_path;
        $id  = null;

        if (preg_match('/youtu\.be\/([^?&]+)/', $url, $m))        $id = $m[1];
        elseif (preg_match('/[?&]v=([^&]+)/', $url, $m))          $id = $m[1];
        elseif (preg_match('/embed\/([^?&]+)/', $url, $m))         $id = $m[1];

        return $id ? "https://www.youtube.com/embed/{$id}" : null;
    }

    public function getSpotifyEmbedUrl(): ?string
    {
        if ($this->file_type !== 'spotify') return null;

        $url = $this->file_path;
        if (preg_match('/spotify\.com\/(track|album|playlist|episode)\/([^?]+)/', $url, $m)) {
            return "https://open.spotify.com/embed/{$m[1]}/{$m[2]}";
        }
        return null;
    }
}