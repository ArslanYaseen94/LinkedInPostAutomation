<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('retry_count')->default(0)->after('status');
            $table->integer('max_retries')->default(3)->after('retry_count');
            $table->integer('impressions')->default(0)->after('linkedin_urn');
            $table->integer('reactions')->default(0)->after('impressions');
            $table->integer('comments')->default(0)->after('reactions');
            $table->integer('clicks')->default(0)->after('comments');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['retry_count', 'max_retries', 'impressions', 'reactions', 'comments', 'clicks']);
        });
    }
};
