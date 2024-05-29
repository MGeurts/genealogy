<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();

            $table->char('iso2', 2)->nullable()->unique();
            $table->char('iso3', 3)->nullable()->unique();
            $table->string('name')->index();
            $table->string('name_nl')->index();
            $table->string('isd', 8)->nullable();
            $table->boolean('is_eu')->default(0)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
};
