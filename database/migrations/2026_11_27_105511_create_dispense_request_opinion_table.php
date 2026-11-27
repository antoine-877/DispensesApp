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

            // Demande concernée
            $table->foreignId('dispense_request_id')
                ->constrained('dispense_requests')
                ->restrictOnDelete();

            // Professeur qui donne son avis
            $table->foreignId('teacher_id')
                ->constrained('users');

            // Avis du professeur
            $table->enum('opinion_type', [
                'favorable',
                'favorable_with_conditions',
                'unfavorable',
            ])->nullable();

            // Justification et conditions
            $table->text('motif')->nullable();
            $table->text('conditions')->nullable();

            // Date de remise de l'avis
            $table->timestamp('given_at')->nullable();

            $table->timestamps();

            // Un seul avis par professeur et par demande
            $table->unique([
                'dispense_request_id',
                'teacher_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispense_request_opinions');
    }
};
