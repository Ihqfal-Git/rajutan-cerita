<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memory_id')->constrained()->onDelete('cascade');
            $table->string('guest_token');
            $table->timestamps();

            $table->unique(['memory_id', 'guest_token']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};