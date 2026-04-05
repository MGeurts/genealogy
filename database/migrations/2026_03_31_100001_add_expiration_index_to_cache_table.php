<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cache', function (Blueprint $table) {
            $table->index('expiration');
        });

        Schema::table('cache_locks', function (Blueprint $table) {
            $table->index('expiration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cache', function (Blueprint $table) {
            $table->dropIndex(['expiration']);
        });

        Schema::table('cache_locks', function (Blueprint $table) {
            $table->dropIndex(['expiration']);
        });
    }
};
