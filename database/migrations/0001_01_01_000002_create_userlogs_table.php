<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('userlogs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            $table->string('country_name', 100)->nullable();
            $table->string('country_code', 2)->nullable();

            $table->timestamps();
        });

        // Add index on country_name
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            DB::statement('CREATE INDEX userlogs_country_name_index ON userlogs(country_name)');
        } else {
            DB::statement('ALTER TABLE `userlogs` ADD INDEX `userlogs_country_name_index` (`country_name`)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('userlogs');
    }
};
