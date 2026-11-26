<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\DispenseRequest;
use App\Models\Document;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DispenseScenarioSeeder extends Seeder
{
    public function run(): void
    {
        $section = Section::create(['name' => 'Informatique']);

        $olivier = User::factory()->teacher()->create([
            'first_name' => 'Olivier',
            'last_name' => 'Lardinois',
            'email' => 'olivier.lardinois@example.com',
        ]);

        $camille = User::factory()->create([
            'section_id' => $section->id,
            'first_name' => 'Camille',
            'last_name' => 'Dethier',
            'email' => 'camille.dethier@example.com',
        ]);

        $thomas = User::factory()->create([
            'section_id' => $section->id,
            'first_name' => 'Thomas',
            'last_name' => 'Collignon',
            'email' => 'thomas.collignon@example.com',
        ]);

        $course = Course::create([
            'section_id' => $section->id,
            'teacher_id' => $olivier->id,
            'name' => 'Initiation aux bases de données',
        ]);

        // Camille : parcours terminé, dispense accordée
        $camilleRequest = DispenseRequest::create([
            'student_id' => $camille->id,
            'course_id' => $course->id,
            'status' => 'accepted',
            'basis' => 'Cours similaire réussi avec 15/20.',
            // dernier dépôt, après l'ajout du descriptif
            'submitted_at' => now()->subDays(8),
            'completed_at' => now()->subDays(7),
            'opinion_type' => 'favorable',
            'opinion_comment' => 'Le cours suivi couvre les acquis attendus.',
            'opinion_given_at' => now()->subDays(2),
            'decision_reason' => 'Dispense accordée après avis favorable.',
            'decided_at' => now()->subDay(),
        ]);

        $this->addDocument($camilleRequest, 'transcript', 'camille-releve-de-cotes', 10);
        $this->addDocument($camilleRequest, 'course_description', 'camille-descriptif-cours', 8);

        // Thomas : même cours, demande seulement déposée
        $thomasRequest = DispenseRequest::create([
            'student_id' => $thomas->id,
            'course_id' => $course->id,
            'status' => 'submitted',
            'basis' => 'Cours similaire suivi précédemment.',
            'submitted_at' => now()->subDay(),
        ]);

        $this->addDocument($thomasRequest, 'transcript', 'thomas-releve-de-cotes', 1);
    }

    private function addDocument(DispenseRequest $request, string $type, string $name, int $daysAgo): void
    {
        // faux fichier texte, juste pour les tests
        $path = "dispenses/demo/{$name}.txt";
        Storage::disk('local')->put($path, "Document fictif : {$name}");

        Document::create([
            'dispense_request_id' => $request->id,
            'type' => $type,
            'file_path' => $path,
            'uploaded_at' => now()->subDays($daysAgo),
        ]);
    }
}
