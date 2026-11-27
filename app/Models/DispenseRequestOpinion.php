<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['opinion_type', 'motif', 'conditions', 'given_at'])]
class DispenseRequestOpinion extends Model
{
    protected function casts(): array
    {
        return ['given_at' => 'datetime'];
    }

    public function dispenseRequest(): BelongsTo
    {
        return $this->belongsTo(DispenseRequest::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id')
            ->where('role', 'teacher');
    }
}
