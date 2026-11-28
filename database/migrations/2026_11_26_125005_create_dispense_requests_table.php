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
        Schema::create('dispense_requests', function (Blueprint $table) {
            $table->id();

            // Étudiant qui introduit la demande
            $table->foreignId('student_id')->constrained('users')->restrictOnDelete();

            // Cours concerné (la section se retrouve via le cours)
            $table->foreignId('course_id')->constrained('courses')->restrictOnDelete();

            // Statut général de la demande
            $table->enum('status', [
                'submitted',          // envoyée ou renvoyée à la coordinatrice
                'incomplete',         // documents manquants, l'étudiant modifie
                'awaiting_opinion',   // envoyée au prof
                'awaiting_decision',  // avis rendu à la coordinatrice
                'accepted',
                'refused',
            ])->default('submitted');

            // Informations fournies par l'étudiant
            $table->text('basis');

            // Gestion des demandes incomplètes
            $table->text('incomplete_reason')->nullable();

            // Dates de suivi
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Décision finale de la coordinatrice
            $table->text('decision_reason')->nullable();
            $table->timestamp('decided_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispense_requests');
    }
};
