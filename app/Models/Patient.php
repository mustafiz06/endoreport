<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'name',
        'date_of_birth',
        'age',
        'gender',
        'phone',
        'email',
        'address',
        'national_id',
        'blood_group',
        'emergency_contact',
        'notes',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'age' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Patient $patient) {
            if (empty($patient->patient_id)) {
                $patient->patient_id = self::generateNextPatientId();
            }
            if (!empty($patient->date_of_birth) && empty($patient->age)) {
                $patient->age = Carbon::parse($patient->date_of_birth)->age;
            }
        });

        static::updating(function (Patient $patient) {
            if ($patient->isDirty('date_of_birth') && !empty($patient->date_of_birth)) {
                $patient->age = Carbon::parse($patient->date_of_birth)->age;
            }
        });
    }

    public static function generateNextPatientId(): string
    {
        $lastPatient = self::withTrashed()
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastPatient && preg_match('/P-(\d+)/', $lastPatient->patient_id, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        }

        return 'P-' . str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
    }

    public function examinations(): HasMany
    {
        return $this->hasMany(Examination::class)->orderBy('examination_date', 'desc')->orderBy('examination_time', 'desc');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class)->latest();
    }

    public function images(): HasMany
    {
        return $this->hasMany(CapturedImage::class)->latest('captured_at');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(RecordedVideo::class)->latest('recorded_at');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function hasMedicalHistory(): bool
    {
        return $this->examinations()->exists();
    }

    public function getComputedAgeAttribute(): ?int
    {
        if ($this->date_of_birth) {
            return Carbon::parse($this->date_of_birth)->age;
        }
        return $this->age;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'Active');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('patient_id', 'like', "%{$term}%")
              ->orWhere('name', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('national_id', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }
}
