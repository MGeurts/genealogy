<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('userlogs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            $table->string('country_name', 100)->nullable();
            $table->string('country_code', 2)->nullable();

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
        });

        // Add index on country_name
        DB::statement('ALTER TABLE `userlogs` ADD INDEX `userlogs_country_name_index` (`country_name`)');
    }

    public function down()
    {
        Schema::dropIfExists('userlogs');
    }
};
