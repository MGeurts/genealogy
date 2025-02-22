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
        Schema::create('couples', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('person1_id')->index();
            $table->unsignedBigInteger('person2_id')->index();

            $table->date('date_start')->nullable()->index();
            $table->date('date_end')->nullable();

            $table->boolean('is_married')->default(0);
            $table->boolean('has_ended')->default(0);

            $table->unsignedBigInteger('team_id')->nullable()->index();
            // ---------------------------------------------------------------------
            $table->timestamps();

            $table->unique(['person1_id', 'person2_id', 'date_start']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('couples');
    }
};
