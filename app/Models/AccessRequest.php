<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessRequest extends Model
{
    protected $fillable = [
        'memory_id', 'guest_token', 'guest_name', 'status',
    ];

    public function memory()
    {
        return $this->belongsTo(Memory::class);
    }
}