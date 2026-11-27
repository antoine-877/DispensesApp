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
        $camille = $this->user('student', 'Camille', 'Dethier', 'camille.dethier@example.com');
        $thomas = $this->user('student', 'Thomas', 'Collignon', 'thomas.collignon@example.com');

        $course = Course::firstOrCreate([
            'section_id' => $section->id,
            'name' => 'Initiation aux bases de données',
        ], [
            'session_count' => 24,
        ]);
        $course->teachers()->syncWithoutDetaching([$olivier->id]);

        $camilleRequest = $this->request($camille, $course, [
            'section_id' => $section->id,
            'status' => 'accepted',
            'basis' => 'Cours similaire réussi avec 15/20.',
            'submitted_at' => now()->subDays(8),
            'completed_at' => now()->subDays(7),
            'decision_motif' => 'Dispense accordée après avis favorable.',
            'decided_at' => now()->subDay(),
        ]);

        $this->opinion($camilleRequest, $olivier, [
            'opinion_type' => 'favorable',
            'motif' => 'Le cours suivi couvre les acquis attendus.',
            'given_at' => now()->subDays(2),
        ]);

        $this->addDocument($camilleRequest, 'transcript', 'camille-releve-de-cotes', 10);
        $this->addDocument($camilleRequest, 'course_description', 'camille-descriptif-cours', 8);

        $thomasRequest = $this->request($thomas, $course, [
            'section_id' => $section->id,
            'status' => 'submitted',
            'basis' => 'Cours similaire suivi précédemment.',
            'submitted_at' => now()->subDay(),
        ]);

        $this->addDocument($thomasRequest, 'transcript', 'thomas-releve-de-cotes', 1);
    }

    private function user(string $role, string $firstName, string $lastName, string $email): User
    {
        $user = User::firstOrNew(['email' => $email]);

        if (! $user->exists) {
            $user->password = 'password';
            $user->email_verified_at = now();
        }

        $user->forceFill([
            'name' => "{$firstName} {$lastName}",
            'first_name' => $firstName,
            'last_name' => $lastName,
            'role' => $role,
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

    /** @param array<string, mixed> $attributes */
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
