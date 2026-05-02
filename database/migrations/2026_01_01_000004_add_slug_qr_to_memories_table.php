<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memories', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('id');
            $table->string('qr_path')->nullable()->after('external_link');
            $table->integer('view_count')->default(0)->after('qr_path');
            $table->timestamp('last_accessed_at')->nullable()->after('view_count');
        });
    }

    public function down(): void
    {
        Schema::table('memories', function (Blueprint $table) {
            $table->dropColumn(['slug', 'qr_path', 'view_count', 'last_accessed_at']);
        });
    }
};