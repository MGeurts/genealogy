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
            $table->index(['team_id', 'father_id'], 'people_teamid_fatherid_index');
            $table->index(['team_id', 'mother_id'], 'people_teamid_motherid_index');
            $table->index(['team_id', 'parents_id'], 'people_teamid_parentsid_index');
            $table->index(['team_id', 'dob'], 'people_teamid_dob_index');
            $table->index(['team_id', 'surname', 'firstname'], 'people_surname_firstname_index');
            $table->index(['firstname', 'surname'], 'people_firstname_surname_index');
            $table->index(['father_id', 'mother_id'], 'people_fatherid_motherid_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('people', function (Blueprint $table): void {
            // Drop the indexes
            $table->dropIndex('people_teamid_fatherid_index');
            $table->dropIndex('people_teamid_motherid_index');
            $table->dropIndex('people_teamid_parentsid_index');
            $table->dropIndex('people_teamid_dob_index');
            $table->dropIndex('people_surname_firstname_index');
            $table->dropIndex('people_firstname_surname_index');
            $table->dropIndex('people_fatherid_motherid_index');
        });
    }
};
