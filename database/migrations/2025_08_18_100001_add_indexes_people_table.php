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
        Schema::table('people', function (Blueprint $table): void {
            $table->index(['team_id', 'father_id']);
            $table->index(['team_id', 'mother_id']);
            $table->index(['team_id', 'parents_id']);
            $table->index(['team_id', 'dob']);
            $table->index(['firstname', 'surname']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
