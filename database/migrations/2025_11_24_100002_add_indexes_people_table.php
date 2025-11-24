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
            $table->index(['team_id', 'father_id', 'mother_id'], 'people_teamid_parents_index');
            $table->index(['team_id', 'yob'], 'people_teamid_yob_index');

            $table->fullText(['firstname', 'surname', 'birthname', 'nickname'], 'people_names_fulltext_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('people', function (Blueprint $table): void {
            // Drop the indexes
            $table->dropIndex('people_teamid_parents_index');
            $table->dropIndex('people_teamid_yob_index');

            $table->dropIndex('people_names_fulltext_index');
        });
    }
};
