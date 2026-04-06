<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('link_url');
            $table->string('image_alt_text')->nullable()->after('image_path');
            $table->string('video_path')->nullable()->after('image_alt_text');
            $table->string('video_alt_text')->nullable()->after('video_path');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'image_alt_text', 'video_path', 'video_alt_text']);
        });
    }
};
