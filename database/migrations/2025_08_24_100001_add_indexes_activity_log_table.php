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
        Schema::connection(config('activitylog.database_connection'))
            ->table(config('activitylog.table_name'), function (Blueprint $table): void {
                // Composite index for the common query pattern (covers both person_couple and user_team logs)
                // This covers queries filtering by log_name, team_id, and updated_at
                $table->index(['log_name', 'team_id', 'updated_at'], 'idx_activity_log_performance');

                $table->index('team_id', 'idx_activity_log_team');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(config('activitylog.database_connection'))
            ->table(config('activitylog.table_name'), function (Blueprint $table): void {
                // Drop the indexes
                $table->dropIndex('idx_activity_log_performance');
                $table->dropIndex('idx_activity_log_team');
            });
    }
};
