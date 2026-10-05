<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'System Administrator',
                'email' => 'admin@hospital.com',
                'phone' => '+1 (555) 100-2000',
                'designation' => 'Lead Hospital IT Administrator',
                'status' => 'Active',
                'role' => 'Super Admin',
            ],
            [
                'name' => 'Dr. Robert Bennett, MD',
                'email' => 'doctor@hospital.com',
                'phone' => '+1 (555) 100-2001',
                'designation' => 'Chief of Endoscopy & Gastroenterology',
                'status' => 'Active',
                'role' => 'Doctor',
            ],
            [
                'name' => 'Sarah Jenkins',
                'email' => 'technician@hospital.com',
                'phone' => '+1 (555) 100-2002',
                'designation' => 'Lead Endoscopy Suite Technician',
                'status' => 'Active',
                'role' => 'Technician',
            ],
            [
                'name' => 'Emily Clarke',
                'email' => 'reception@hospital.com',
                'phone' => '+1 (555) 100-2003',
                'designation' => 'Front Desk & Patient Intake Coordinator',
                'status' => 'Active',
                'role' => 'Reception',
            ],
            [
                'name' => 'David Morales',
                'email' => 'reporter@hospital.com',
                'phone' => '+1 (555) 100-2004',
                'designation' => 'Clinical Medical Transcriptionist',
                'status' => 'Active',
                'role' => 'Reporter',
            ],
        ];

        foreach ($users as $data) {
            $roleName = $data['role'];
            unset($data['role']);

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ])
            );

            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }
        }
    }
}
