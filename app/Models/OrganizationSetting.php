<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'address',
        'phone',
        'email',
        'website',
        'registration_number',
        'license_number',
        'report_header',
        'report_footer',
        'doctor_signature',
        'stamp',
        'default_report_font',
        'default_report_format',
    ];

    protected function casts(): array
    {
        return [
            'doctor_signature' => 'boolean',
        ];
    }

    public static function current(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Apex Endoscopy & Diagnostic Center',
                'address' => 'Suite 400, Medical Plaza, Hospital Avenue',
                'phone' => '+1 (555) 234-5678',
                'email' => 'info@apex-endoscopy.com',
                'website' => 'https://apex-endoscopy.example.com',
                'registration_number' => 'MED-REG-2026-9812',
                'license_number' => 'LIC-DGHS-88231',
                'report_header' => 'APEX ADVANCED ENDOSCOPY & DIAGNOSTIC CENTER',
                'report_footer' => 'This electronic diagnostic report is verified by authorized medical practitioners.',
                'doctor_signature' => true,
                'default_report_font' => 'Arial',
                'default_report_format' => 'A4',
            ]
        );
    }
}
