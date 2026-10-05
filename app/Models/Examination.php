<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Examination extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'examination_id',
        'patient_id',
        'procedure_id',
        'doctor_id',
        'referring_doctor_id',
        'instrument_id',
        'indication',
        'examination_date',
        'examination_time',
        'notes',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'examination_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Examination $examination) {
            if (empty($examination->examination_id)) {
                $examination->examination_id = self::generateNextExaminationId();
            }
        });
    }

    public static function generateNextExaminationId(): string
    {
        $year = date('Y');
        $prefix = "EX-{$year}-";

        $lastExam = self::withTrashed()
            ->where('examination_id', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastExam && preg_match('/EX-\d{4}-(\d+)/', $lastExam->examination_id, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        }

        return $prefix . str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class)->withTrashed();
    }

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function referringDoctor(): BelongsTo
    {
        return $this->belongsTo(ReferringDoctor::class);
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    public function report(): HasOne
    {
        return $this->hasOne(Report::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(CapturedImage::class)->orderBy('sequence', 'asc');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(RecordedVideo::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isFinalized(): bool
    {
        return $this->status === 'Finalized';
    }

    public function canBeEdited(): bool
    {
        return $this->status !== 'Finalized' && $this->status !== 'Cancelled';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'Scheduled' => 'badge-info',
            'In Progress' => 'badge-primary',
            'Captured' => 'badge-warning',
            'Reporting' => 'badge-secondary',
            'Reviewed' => 'badge-purple',
            'Finalized' => 'badge-success',
            'Cancelled' => 'badge-danger',
            default => 'badge-light',
        };
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('examination_date', Carbon::today());
    }

    public function scopePendingReports(Builder $query): Builder
    {
        return $query->whereIn('status', ['Captured', 'Reporting', 'Reviewed'])
                     ->whereDoesntHave('report', function ($q) {
                         $q->where('status', 'Finalized');
                     });
    }
}
