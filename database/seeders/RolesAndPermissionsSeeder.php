<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Patient permissions
            ['name' => 'patient.view', 'module' => 'Patient', 'description' => 'View patient records and history'],
            ['name' => 'patient.create', 'module' => 'Patient', 'description' => 'Create new patient records'],
            ['name' => 'patient.edit', 'module' => 'Patient', 'description' => 'Edit patient details'],
            ['name' => 'patient.delete', 'module' => 'Patient', 'description' => 'Delete or archive patient records'],

            // Doctor permissions
            ['name' => 'doctor.view', 'module' => 'Doctor', 'description' => 'View doctor directory'],
            ['name' => 'doctor.create', 'module' => 'Doctor', 'description' => 'Add new doctors'],
            ['name' => 'doctor.edit', 'module' => 'Doctor', 'description' => 'Update doctor profile'],
            ['name' => 'doctor.delete', 'module' => 'Doctor', 'description' => 'Deactivate or delete doctor'],

            // Referring Doctor permissions
            ['name' => 'referring_doctor.view', 'module' => 'Referring Doctor', 'description' => 'View referring doctors'],
            ['name' => 'referring_doctor.create', 'module' => 'Referring Doctor', 'description' => 'Add referring doctors'],
            ['name' => 'referring_doctor.edit', 'module' => 'Referring Doctor', 'description' => 'Edit referring doctors'],
            ['name' => 'referring_doctor.delete', 'module' => 'Referring Doctor', 'description' => 'Delete referring doctors'],

            // Procedure permissions
            ['name' => 'procedure.view', 'module' => 'Procedure', 'description' => 'View procedures'],
            ['name' => 'procedure.create', 'module' => 'Procedure', 'description' => 'Create procedures'],
            ['name' => 'procedure.edit', 'module' => 'Procedure', 'description' => 'Update procedures'],
            ['name' => 'procedure.delete', 'module' => 'Procedure', 'description' => 'Delete procedures'],

            // Instrument permissions
            ['name' => 'instrument.view', 'module' => 'Instrument', 'description' => 'View endoscopic instruments'],
            ['name' => 'instrument.create', 'module' => 'Instrument', 'description' => 'Add instruments'],
            ['name' => 'instrument.edit', 'module' => 'Instrument', 'description' => 'Update instruments'],
            ['name' => 'instrument.delete', 'module' => 'Instrument', 'description' => 'Delete instruments'],

            // Examination permissions
            ['name' => 'examination.view', 'module' => 'Examination', 'description' => 'View examination details'],
            ['name' => 'examination.create', 'module' => 'Examination', 'description' => 'Schedule/create examinations'],
            ['name' => 'examination.edit', 'module' => 'Examination', 'description' => 'Edit examination information'],
            ['name' => 'examination.finalize', 'module' => 'Examination', 'description' => 'Finalize clinical examination'],

            // Reporting permissions
            ['name' => 'report.view', 'module' => 'Report', 'description' => 'View diagnostic reports'],
            ['name' => 'report.create', 'module' => 'Report', 'description' => 'Create diagnostic reports'],
            ['name' => 'report.edit', 'module' => 'Report', 'description' => 'Edit draft reports'],
            ['name' => 'report.finalize', 'module' => 'Report', 'description' => 'Sign off and finalize report'],
            ['name' => 'report.print', 'module' => 'Report', 'description' => 'Print diagnostic reports'],
            ['name' => 'report.pdf', 'module' => 'Report', 'description' => 'Export reports to PDF'],

            // Capture permissions
            ['name' => 'capture.view', 'module' => 'Capture', 'description' => 'View media capture window'],
            ['name' => 'capture.image', 'module' => 'Capture', 'description' => 'Capture and store still images'],
            ['name' => 'capture.video', 'module' => 'Capture', 'description' => 'Record diagnostic videos'],

            // Audit & System
            ['name' => 'audit.view', 'module' => 'Audit', 'description' => 'View clinical audit logs'],
            ['name' => 'user.view', 'module' => 'User', 'description' => 'View system users'],
            ['name' => 'user.create', 'module' => 'User', 'description' => 'Add new users'],
            ['name' => 'user.edit', 'module' => 'User', 'description' => 'Modify user accounts'],
            ['name' => 'user.delete', 'module' => 'User', 'description' => 'Suspend or delete users'],
            ['name' => 'role.view', 'module' => 'Role', 'description' => 'View roles and permissions'],
            ['name' => 'role.edit', 'module' => 'Role', 'description' => 'Manage roles and assign permissions'],
            ['name' => 'setting.view', 'module' => 'Settings', 'description' => 'View organization settings'],
            ['name' => 'setting.edit', 'module' => 'Settings', 'description' => 'Modify organization settings'],
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p['name']], $p);
        }

        // Roles
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full administrative access to all clinical and system modules',
                'permissions' => array_column($permissions, 'name'),
            ],
            [
                'name' => 'Doctor',
                'slug' => 'doctor',
                'description' => 'Endoscopist / Consultant physician managing examinations, procedures and reports',
                'permissions' => [
                    'patient.view', 'patient.create', 'patient.edit',
                    'doctor.view', 'referring_doctor.view', 'procedure.view', 'instrument.view',
                    'examination.view', 'examination.create', 'examination.edit', 'examination.finalize',
                    'report.view', 'report.create', 'report.edit', 'report.finalize', 'report.print', 'report.pdf',
                    'capture.view', 'capture.image', 'capture.video',
                    'audit.view'
                ],
            ],
            [
                'name' => 'Technician',
                'slug' => 'technician',
                'description' => 'Endoscopy suite technician handling instruments and live capture',
                'permissions' => [
                    'patient.view', 'procedure.view', 'instrument.view', 'instrument.edit',
                    'examination.view', 'capture.view', 'capture.image', 'capture.video',
                ],
            ],
            [
                'name' => 'Reception',
                'slug' => 'reception',
                'description' => 'Front desk staff registering patients and scheduling examinations',
                'permissions' => [
                    'patient.view', 'patient.create', 'patient.edit',
                    'doctor.view', 'referring_doctor.view', 'referring_doctor.create',
                    'procedure.view', 'examination.view', 'examination.create',
                ],
            ],
            [
                'name' => 'Reporter',
                'slug' => 'reporter',
                'description' => 'Medical transcriptionist / reporter typing and preparing findings',
                'permissions' => [
                    'patient.view', 'examination.view',
                    'report.view', 'report.create', 'report.edit', 'report.print', 'report.pdf',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate(
                ['slug' => $roleData['slug']],
                ['name' => $roleData['name'], 'description' => $roleData['description']]
            );

            $permIds = Permission::whereIn('name', $roleData['permissions'])->pluck('id');
            $role->permissions()->sync($permIds);
        }
    }
}
