<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $dispense_request_id
 * @property string $type
 * @property string $file_path
 * @property Carbon $uploaded_at
 */
#[Fillable(['type', 'file_path'])]
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
