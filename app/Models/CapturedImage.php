<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CapturedImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'examination_id',
        'filename',
        'path',
        'captured_at',
        'captured_by',
        'sequence',
        'caption',
    ];

    protected function casts(): array
    {
        return [
            'captured_at' => 'datetime',
            'sequence' => 'integer',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function examination(): BelongsTo
    {
        return $this->belongsTo(Examination::class);
    }

    public function capturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'captured_by');
    }
}
