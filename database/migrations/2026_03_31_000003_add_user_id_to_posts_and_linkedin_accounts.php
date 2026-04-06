<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->nullable()->after('id')->constrained()->nullOnDelete();
            $table->index(['user_id', 'status']);
        });

        Schema::table('linkedin_accounts', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('linkedin_accounts', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
            $table->dropConstrainedForeignIdFor(User::class);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropConstrainedForeignIdFor(User::class);
        });
    }
};

