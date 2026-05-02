<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $memories = DB::table('memories')->whereNotNull('file_path')->get();

        foreach ($memories as $memory) {
            DB::table('memory_files')->insert([
                'memory_id'  => $memory->id,
                'file_path'  => $memory->file_path,
                'file_type'  => $memory->type ?? 'image',
                'caption'    => null,
                'order'      => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Migrate external_link juga
        $withLinks = DB::table('memories')->whereNotNull('external_link')->get();
        foreach ($withLinks as $memory) {
            $linkType = 'link';
            $link = $memory->external_link;
            if (str_contains($link, 'youtube.com') || str_contains($link, 'youtu.be')) {
                $linkType = 'youtube';
            } elseif (str_contains($link, 'spotify.com')) {
                $linkType = 'spotify';
            }

            DB::table('memory_files')->insert([
                'memory_id'  => $memory->id,
                'file_path'  => $link,
                'file_type'  => $linkType,
                'caption'    => null,
                'order'      => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('memory_files')->truncate();
    }
};