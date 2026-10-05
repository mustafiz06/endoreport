<?php

namespace Database\Seeders;

use App\Models\ReferringDoctor;
use Illuminate\Database\Seeder;

class ReferringDoctorSeeder extends Seeder
{
    public function run(): void
    {
        $referringDoctors = [
            [
                'name' => 'Dr. Thomas Gallagher',
                'qualification' => 'MBBS, FCPS (Medicine)',
                'specialization' => 'Internal Medicine',
                'hospital_chamber' => 'Metropolitan General Hospital, OPD Room 204',
                'phone' => '+1 (555) 440-1212',
                'email' => 't.gallagher@metro-health.org',
                'address' => '120 West Central Parkway, New York',
                'status' => 'Active',
            ],
            [
                'name' => 'Dr. Samantha Reed',
                'qualification' => 'MBBS, MS (General Surgery)',
                'specialization' => 'General & Laparoscopic Surgery',
                'hospital_chamber' => 'St. Luke Surgical Clinic, Chamber 3',
                'phone' => '+1 (555) 440-3434',
                'email' => 'samantha.reed@stlukes.org',
                'address' => '850 North Broadway, New York',
                'status' => 'Active',
            ],
            [
                'name' => 'Dr. Michael Chen',
                'qualification' => 'MBBS, MD (Chest Diseases)',
                'specialization' => 'Respiratory Medicine',
                'hospital_chamber' => 'City Pulmonary Associates',
                'phone' => '+1 (555) 440-5656',
                'email' => 'mchen@citychest.org',
                'address' => '33 East 68th Street, New York',
                'status' => 'Active',
            ],
        ];

        foreach ($referringDoctors as $doc) {
            ReferringDoctor::firstOrCreate(['name' => $doc['name']], $doc);
        }
    }
}
