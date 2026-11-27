<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['section_id', 'name', 'session_count'])]
class Course extends Model
{
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'course_teacher',
            'course_id',
            'teacher_id'
        )->where('users.role', 'teacher');
    }

    public function dispenseRequests(): HasMany
    {
        return $this->hasMany(DispenseRequest::class);
    }
}
