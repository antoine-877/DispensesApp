<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $section_id
 * @property string $role
 * @property string $first_name
 * @property string $last_name
 * @property-read string $name  (accesseur calculé, pas de colonne)
 * @property string $email
 * @property string|null $national_number
 * @property string|null $address
 * @property string|null $phone
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'first_name',
    'last_name',
    'email',
    'password',
    'national_number',
    'address',
    'phone',
])]
#[Hidden([
    'password',
    'two_factor_secret',
    'two_factor_recovery_codes',
    'remember_token',
    'national_number',
])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Inclut "name" quand le user est sérialisé (Inertia, JSON)
    protected $appends = ['name'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'national_number' => 'encrypted',
        ];
    }

    // Accesseur calculé : pas de colonne en base
    protected function name(): Attribute
    {
        return Attribute::get(
            fn() => trim("{$this->first_name} {$this->last_name}")
        );
    }

    // Relations

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    // Étudiant : ses demandes de dispense
    public function dispenseRequests(): HasMany
    {
        return $this->hasMany(DispenseRequest::class, 'student_id');
    }

    // Professeur : ses cours
    public function taughtCourses(): BelongsToMany
    {
        return $this->belongsToMany(
            Course::class,
            'course_teacher',
            'teacher_id',
            'course_id'
        );
    }

    // Professeur : les avis qu'on lui demande
    public function dispenseRequestOpinions(): HasMany
    {
        return $this->hasMany(DispenseRequestOpinion::class, 'teacher_id');
    }

    // Rôles

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isCoordinator(): bool
    {
        return $this->role === 'coordinator';
    }
}
