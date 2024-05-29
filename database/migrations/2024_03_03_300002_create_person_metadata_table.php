<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_metadata', function (Blueprint $table) {
            $table->id();

            $table->foreignId('person_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            $table->string('key')->index();
            $table->string('value')->nullable();

            $table->timestamps();

            $table->unique(['person_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_metadata');
    }
}
