<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $dispense_request_id
 * @property int $teacher_id
 * @property string|null $opinion_type
 * @property string|null $opinion_comment
 * @property Carbon|null $opinion_given_at
 */
#[Fillable(['opinion_type', 'opinion_comment'])]
class DispenseRequestOpinion extends Model
{
    protected function casts(): array
    {
        return ['opinion_given_at' => 'datetime'];
    }

    public function isPending(): bool
    {
        return $this->opinion_given_at === null;
    }

    public function dispenseRequest(): BelongsTo
    {
        return $this->belongsTo(DispenseRequest::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
