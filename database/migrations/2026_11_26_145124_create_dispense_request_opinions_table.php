<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispense_request_opinions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('dispense_request_id')
                ->constrained('dispense_requests')
                ->cascadeOnDelete();

            // Prof sollicité (doit enseigner le cours de la demande)
            $table->foreignId('teacher_id')->constrained('users')->restrictOnDelete();

            // Remplis quand le prof répond ; null = avis en attente
            $table->enum('opinion_type', ['favorable', 'unfavorable'])->nullable();
            $table->text('opinion_comment')->nullable(); // obligatoire à la réponse (validation)
            $table->timestamp('opinion_given_at')->nullable();

            $table->timestamps();

            // Un seul avis par prof et par demande
            $table->unique(['dispense_request_id', 'teacher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispense_request_opinions');
    }
};
