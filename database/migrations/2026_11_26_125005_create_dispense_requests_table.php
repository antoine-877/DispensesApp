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

            // Cours et section concernés
            $table->foreignId('course_id')->constrained('courses');
            $table->foreignId('section_id')->constrained('sections');

            // Statut général de la demande
            $table->enum('status', [
                'draft',
                'submitted',
                'incomplete',
                'awaiting_opinion',
                'awaiting_decision',
                'accepted',
                'accepted_with_conditions',
                'refused',
            ])->default('draft');

            // Informations fournies par l'étudiant
            $table->text('basis')->nullable();

            // Gestion des demandes incomplètes
            $table->text('incomplete_reason')->nullable();

            // Dates de suivi
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('archived_at')->nullable();

            // Décision finale de la coordinatrice
            $table->text('decision_motif')->nullable();
            $table->text('decision_conditions')->nullable();
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
