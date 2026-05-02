<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['memory_id', 'guest_token'];

    public function memory()
    {
        return $this->belongsTo(Memory::class);
    }
}