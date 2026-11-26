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
            $table->foreignId('student_id')->constrained('users');

            // Cours et section concernés
            $table->foreignId('course_id')->constrained('courses');
            $table->foreignId('section_id')->constrained('sections');

            // Statut de la demande
            $table->enum('status', [
                'submitted',
                'incomplete',
                'awaiting_opinion',
                'awaiting_decision',
                'accepted',
                'refused'
            ])->default('submitted');

            // Base de la demande (CESS, document, ...)
            $table->text('basis')->nullable();

            // Gestion des demandes incomplètes
            $table->text('incomplete_reason')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();

            // Avis du professeur
            $table->foreignId('teacher_id')->nullable()->constrained('users');
            $table->enum('opinion_type', [
                'favorable',
                'unfavorable'
            ])->nullable();

            $table->text('motif')->nullable();
            $table->timestamp('motif_given_at')->nullable();

            // Décision finale du coordinateur
            $table->enum('final_decision', [
                'accepted',
                'refused'
            ])->nullable();

            $table->text('decision_motif')->nullable();
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
