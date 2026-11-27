<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'course_id',
    'section_id',
    'basis',
    'incomplete_reason',
    'submitted_at',
    'completed_at',
    'decision_motif',
    'decision_conditions',
    'decided_at',
])]
class DispenseRequest extends Model
{
    // Étudiant qui a introduit la demande
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id')
            ->where('role', 'student');
    }

    // Cours concerné par la demande
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Section concernée par la demande
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    // Avis des professeurs concernés
    public function opinions(): HasMany
    {
        return $this->hasMany(DispenseRequestOpinion::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
