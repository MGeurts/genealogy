<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('person_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('person_id')->constrained()->cascadeOnDelete();

            // Event type and details
            $table->string('type'); // baptism, burial, military_service, migration, etc.
            $table->text('description')->nullable();

            // Date handling (same pattern as Person model)
            $table->date('date')->nullable();
            $table->integer('year')->nullable();

            // Location details
            $table->string('place')->nullable();

            $table->string('street', 100)->nullable();
            $table->string('number', 20)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 2)->nullable();

            // Additional metadata (JSON for flexibility)
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['person_id', 'type']);
            $table->index(['person_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('person_events');
    }
};
