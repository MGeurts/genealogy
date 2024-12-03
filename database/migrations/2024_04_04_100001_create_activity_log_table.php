<?php

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
        Schema::connection(config('activitylog.database_connection'))->create(config('activitylog.table_name'), function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('log_name')->nullable()->index();
            $table->text('description');
            $table->nullableMorphs('subject', 'subject');
            $table->string('event')->nullable();
            $table->nullableMorphs('causer', 'causer');
            $table->json('properties')->nullable();
            $table->char('batch_uuid', 36)->nullable();

            $table->foreignId('team_id')->nullable()->constrained()->onDelete('cascade');

            $table->timestamps();
        });

        // Add index on updated_at
        DB::statement('ALTER TABLE `' . config('activitylog.table_name') . '` ADD INDEX `updated_at_index` (`updated_at`)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(config('activitylog.database_connection'))->dropIfExists(config('activitylog.table_name'));
    }
};
