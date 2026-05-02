<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Memory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'description',
        'type',
        'file_path',
        'external_link',
        'qr_path',
        'view_count',
        'last_accessed_at',
    ];

    protected $casts = [
        'last_accessed_at' => 'datetime',
    ];

    // Pemilik
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Multi file (kalau nanti support banyak media)
    public function memoryFiles()
    {
        return $this->hasMany(MemoryFile::class)->orderBy('order');
    }

    // Request akses QR
    public function accessRequests()
    {
        return $this->hasMany(AccessRequest::class);
    }

    // Like
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Komentar
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
}