<?php

namespace Database\Seeders;

use App\Models\OrganizationSetting;
use Illuminate\Database\Seeder;

class OrganizationSettingSeeder extends Seeder
{
    public function run(): void
    {
        OrganizationSetting::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Apex Endoscopy & Diagnostic Center',
                'logo' => null,
                'address' => '742 Evergreen Medical Park, Suite 400, New York, NY 10001',
                'phone' => '+1 (555) 789-0123',
                'email' => 'contact@apex-endoscopy.com',
                'website' => 'https://apex-endoscopy.example.com',
                'registration_number' => 'MED-REG-2026-9812',
                'license_number' => 'LIC-DGHS-88231',
                'report_header' => 'APEX ADVANCED ENDOSCOPY & DIAGNOSTIC CENTER',
                'report_footer' => 'This electronic diagnostic report is verified by authorized medical practitioners. In case of emergency or queries, please contact the endoscopy department directly.',
                'doctor_signature' => true,
                'default_report_font' => 'Arial',
                'default_report_format' => 'A4',
            ]
        );
    }
}
