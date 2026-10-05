<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctorUser = User::where('email', 'doctor@hospital.com')->first();

        $doctors = [
            [
                'user_id' => $doctorUser?->id,
                'name' => 'Prof. Dr. Robert Bennett',
                'qualification' => 'MBBS, MD (Gastroenterology), FRCP (Edin)',
                'specialization' => 'Gastroenterology & Advanced Therapeutic Endoscopy',
                'registration_number' => 'BMDC-REG-44912',
                'phone' => '+1 (555) 301-4455',
                'email' => 'r.bennett@apex-endoscopy.com',
                'status' => 'Active',
            ],
            [
                'user_id' => null,
                'name' => 'Dr. Victoria Martinez',
                'qualification' => 'MBBS, FCPS (Surgery), MS (Hepato-Biliary)',
                'specialization' => 'Surgical Gastroenterology & ERCP Specialist',
                'registration_number' => 'BMDC-REG-55280',
                'phone' => '+1 (555) 301-7788',
                'email' => 'v.martinez@apex-endoscopy.com',
                'status' => 'Active',
            ],
            [
                'user_id' => null,
                'name' => 'Dr. Alan Henderson',
                'qualification' => 'MBBS, MD (Pulmonology), FCCP',
                'specialization' => 'Interventional Pulmonology & Diagnostic Bronchoscopy',
                'registration_number' => 'BMDC-REG-61099',
                'phone' => '+1 (555) 301-9922',
                'email' => 'a.henderson@apex-endoscopy.com',
                'status' => 'Active',
            ],
        ];

        foreach ($doctors as $doc) {
            Doctor::firstOrCreate(['registration_number' => $doc['registration_number']], $doc);
        }
    }
}
