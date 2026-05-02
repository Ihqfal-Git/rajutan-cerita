<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['memory_id', 'guest_token', 'guest_name', 'comment'];

    public function memory()
    {
        return $this->belongsTo(Memory::class);
    }
}