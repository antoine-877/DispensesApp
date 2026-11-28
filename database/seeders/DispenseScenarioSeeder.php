<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\DispenseRequest;
use App\Models\DispenseRequestOpinion;
use App\Models\Document;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DispenseScenarioSeeder extends Seeder
{
    public function run(): void
    {
        $section = Section::firstOrCreate(['name' => 'Informatique']);

        $olivier = $this->user('teacher', 'Olivier', 'Lardinois', 'olivier.lardinois@example.com');
        $nadia = $this->user('teacher', 'Nadia', 'Renard', 'nadia.renard@example.com');
        $camille = $this->user('student', 'Camille', 'Dethier', 'camille.dethier@example.com', $section);
        $thomas = $this->user('student', 'Thomas', 'Collignon', 'thomas.collignon@example.com', $section);
        $lea = $this->user('student', 'Léa', 'Gilson', 'lea.gilson@example.com', $section);

        $course = Course::firstOrCreate([
            'section_id' => $section->id,
            'name' => 'Initiation aux bases de données',
        ], [
            'session_count' => 24,
        ]);
        $course->teachers()->syncWithoutDetaching([$olivier->id, $nadia->id]);

        // Camille : dispense acceptée, les deux profs ont rendu un avis favorable
        $camilleRequest = $this->request($camille, $course, [
            'status' => DispenseRequest::STATUS_ACCEPTED,
            'basis' => 'Cours similaire réussi avec 15/20.',
            'submitted_at' => now()->subDays(8),
            'completed_at' => now()->subDays(7),
            'decision_reason' => 'Dispense accordée après avis favorables.',
            'decided_at' => now()->subDay(),
        ]);

        $this->opinion($camilleRequest, $olivier, [
            'opinion_type' => DispenseRequest::OPINION_FAVORABLE,
            'opinion_comment' => 'Le cours suivi couvre les acquis attendus.',
            'opinion_given_at' => now()->subDays(3),
        ]);
        $this->opinion($camilleRequest, $nadia, [
            'opinion_type' => DispenseRequest::OPINION_FAVORABLE,
            'opinion_comment' => 'Contenu et volume horaire équivalents.',
            'opinion_given_at' => now()->subDays(2),
        ]);

        $this->addDocument($camilleRequest, 'transcript', 'camille-releve-de-cotes', 10);
        $this->addDocument($camilleRequest, 'course_description', 'camille-descriptif-cours', 8);

        // Thomas : demande envoyée, en attente de la coordinatrice (aucun avis demandé)
        $thomasRequest = $this->request($thomas, $course, [
            'status' => DispenseRequest::STATUS_SUBMITTED,
            'basis' => 'Cours similaire suivi précédemment.',
            'submitted_at' => now()->subDay(),
        ]);

        $this->addDocument($thomasRequest, 'transcript', 'thomas-releve-de-cotes', 1);

        // Léa : dossier envoyé aux deux profs, Olivier a répondu, Nadia est encore en attente
        $leaRequest = $this->request($lea, $course, [
            'status' => DispenseRequest::STATUS_AWAITING_OPINION,
            'basis' => 'Module équivalent validé dans une autre école.',
            'submitted_at' => now()->subDays(5),
            'completed_at' => now()->subDays(4),
        ]);

        $this->opinion($leaRequest, $olivier, [
            'opinion_type' => DispenseRequest::OPINION_UNFAVORABLE,
            'opinion_comment' => 'Le programme ne couvre pas la partie SQL avancée.',
            'opinion_given_at' => now()->subDay(),
        ]);
        $this->opinion($leaRequest, $nadia, []); // avis demandé, pas encore rendu

        $this->addDocument($leaRequest, 'transcript', 'lea-releve-de-cotes', 5);
    }

    private function user(string $role, string $firstName, string $lastName, string $email, ?Section $section = null): User
    {
        $user = User::firstOrNew(['email' => $email]);

        if (! $user->exists) {
            $user->password = 'password';
            $user->email_verified_at = now();
        }

        $user->forceFill([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'role' => $role,
            'section_id' => $role === 'student' ? $section?->id : null,
        ])->save();

        return $user;
    }

    /** @param array<string, mixed> $attributes */
    private function request(User $student, Course $course, array $attributes): DispenseRequest
    {
        $request = DispenseRequest::firstOrNew([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'basis' => $attributes['basis'],
        ]);
        $request->forceFill([
            'student_id' => $student->id,
            'course_id' => $course->id,
            ...$attributes,
        ])->save();

        return $request;
    }

    /** @param array<string, mixed> $attributes vide = avis demandé mais pas encore rendu */
    private function opinion(DispenseRequest $request, User $teacher, array $attributes): void
    {
        $opinion = DispenseRequestOpinion::firstOrNew([
            'dispense_request_id' => $request->id,
            'teacher_id' => $teacher->id,
        ]);
        $opinion->forceFill($attributes)->save();
    }

    private function addDocument(DispenseRequest $request, string $type, string $name, int $daysAgo): void
    {
        // faux fichier texte, juste pour les tests
        $path = "dispenses/demo/{$name}.txt";
        Storage::disk('local')->put($path, "Document fictif : {$name}");

        $document = Document::firstOrNew([
            'dispense_request_id' => $request->id,
            'type' => $type,
            'file_path' => $path,
        ]);
        $document->forceFill(['uploaded_at' => now()->subDays($daysAgo)])->save();
    }
}
