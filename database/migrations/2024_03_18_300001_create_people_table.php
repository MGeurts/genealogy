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
        Schema::create('people', function (Blueprint $table) {
            $table->id();

            $table->string('firstname')->nullable();
            $table->string('surname');
            $table->string('birthname')->nullable();
            $table->string('nickname')->nullable();

            $table->string('sex', 1)->default('m')->index();
            $table->foreignId('gender_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('restrict');

            $table->unsignedBigInteger('father_id')->nullable()->index();
            $table->unsignedBigInteger('mother_id')->nullable()->index();
            $table->unsignedBigInteger('parents_id')->nullable()->index();

            $table->date('dob')->nullable();
            $table->integer('yob')->nullable();
            $table->string('pob')->nullable();
            $table->unsignedTinyInteger('birth_order')->nullable();
            $table->date('dod')->nullable();
            $table->integer('yod')->nullable();
            $table->string('pod')->nullable();

            $table->string('street', 100)->nullable();
            $table->string('number', 20)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->foreignId('country_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->string('phone', 50)->nullable();

            $table->string('photo')->nullable();

            $table->unsignedBigInteger('team_id')->nullable()->index();
            // ---------------------------------------------------------------------
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
