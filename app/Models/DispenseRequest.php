<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $student_id
 * @property int $course_id
 * @property string $status
 * @property string $basis
 * @property string|null $incomplete_reason
 * @property Carbon|null $submitted_at
 * @property Carbon|null $completed_at
 * @property string|null $decision_reason
 * @property Carbon|null $decided_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'course_id',
    'basis',
])]
class DispenseRequest extends Model
{
    // Statuts
    public const STATUS_SUBMITTED = 'submitted';                 // envoyée ou renvoyée à la coordinatrice
    public const STATUS_INCOMPLETE = 'incomplete';               // documents manquants, l'étudiant modifie
    public const STATUS_AWAITING_OPINION = 'awaiting_opinion';   // envoyée aux profs du cours
    public const STATUS_AWAITING_DECISION = 'awaiting_decision'; // tous les avis rendus, la coordinatrice décide
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REFUSED = 'refused';

    // Types d'avis (utilisés par DispenseRequestOpinion)
    public const OPINION_FAVORABLE = 'favorable';
    public const OPINION_UNFAVORABLE = 'unfavorable';

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'completed_at' => 'datetime',
            'decided_at' => 'datetime',
        ];
    }

    // Étudiant qui a introduit la demande
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Cours concerné par la demande
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Avis demandés aux profs du cours (un par prof)
    public function opinions(): HasMany
    {
        return $this->hasMany(DispenseRequestOpinion::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
