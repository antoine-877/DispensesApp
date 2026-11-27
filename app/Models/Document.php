<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['type', 'file_path', 'uploaded_at'])]
class Document extends Model
{
    protected function casts(): array
    {
        return ['uploaded_at' => 'datetime'];
    }

    public function dispenseRequest(): BelongsTo
    {
        return $this->belongsTo(DispenseRequest::class);
    }
}
