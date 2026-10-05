<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $creator = User::first();

        $patients = [
            [
                'patient_id' => 'P-000001',
                'name' => 'Eleanor Vance',
                'date_of_birth' => '1979-04-12',
                'age' => 47,
                'gender' => 'Female',
                'phone' => '+1 (555) 712-3001',
                'email' => 'eleanor.vance@example.com',
                'address' => '422 Elmwood Street, Apartment 3B, New York, NY',
                'national_id' => 'NID-99238411',
                'blood_group' => 'O+',
                'emergency_contact' => 'Arthur Vance (Husband) - +1 (555) 712-3002',
                'notes' => 'Known case of GERD with recurrent epigastric burning pain. No drug allergies.',
                'status' => 'Active',
                'created_by' => $creator?->id,
            ],
            [
                'patient_id' => 'P-000002',
                'name' => 'James Harrison',
                'date_of_birth' => '1965-08-25',
                'age' => 61,
                'gender' => 'Male',
                'phone' => '+1 (555) 712-4002',
                'email' => 'jharrison@example.com',
                'address' => '18 Magnolia Boulevard, White Plains, NY',
                'national_id' => 'NID-44129800',
                'blood_group' => 'A+',
                'emergency_contact' => 'Martha Harrison (Wife) - +1 (555) 712-4003',
                'notes' => 'Routine screening colonoscopy recommended due to family history of colonic polyps.',
                'status' => 'Active',
                'created_by' => $creator?->id,
            ],
            [
                'patient_id' => 'P-000003',
                'name' => 'Amina Siddiqui',
                'date_of_birth' => '1992-11-03',
                'age' => 33,
                'gender' => 'Female',
                'phone' => '+1 (555) 712-5003',
                'email' => 'amina.s@example.com',
                'address' => '910 Park Avenue, Suite 12, Manhattan, NY',
                'national_id' => 'NID-88301145',
                'blood_group' => 'B+',
                'emergency_contact' => 'Tariq Siddiqui (Brother) - +1 (555) 712-5004',
                'notes' => 'Persistent dyspepsia and early satiety unresponsive to PPI therapy.',
                'status' => 'Active',
                'created_by' => $creator?->id,
            ],
            [
                'patient_id' => 'P-000004',
                'name' => 'Marcus Thorne',
                'date_of_birth' => '1958-02-17',
                'age' => 68,
                'gender' => 'Male',
                'phone' => '+1 (555) 712-6004',
                'email' => 'mthorne58@example.com',
                'address' => '305 Riverside Drive, New York, NY',
                'national_id' => 'NID-11029384',
                'blood_group' => 'AB-',
                'emergency_contact' => 'Clara Thorne (Daughter) - +1 (555) 712-6005',
                'notes' => 'Chronic cough with mild hemoptysis. Non-smoker. CT chest showed mild peribronchial thickening.',
                'status' => 'Active',
                'created_by' => $creator?->id,
            ],
            [
                'patient_id' => 'P-000005',
                'name' => 'Sophia Delgado',
                'date_of_birth' => '1988-06-30',
                'age' => 38,
                'gender' => 'Female',
                'phone' => '+1 (555) 712-7005',
                'email' => 'sophia.delgado@example.com',
                'address' => '77 West End Terrace, Brooklyn, NY',
                'national_id' => 'NID-55728192',
                'blood_group' => 'O-',
                'emergency_contact' => 'Carlos Delgado (Father) - +1 (555) 712-7006',
                'notes' => 'Iron deficiency anemia of unknown etiology. Referred for bidirectional endoscopy.',
                'status' => 'Active',
                'created_by' => $creator?->id,
            ],
        ];

        foreach ($patients as $p) {
            Patient::firstOrCreate(['patient_id' => $p['patient_id']], $p);
        }
    }
}
