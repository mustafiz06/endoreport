<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            OrganizationSettingSeeder::class,
            DoctorSeeder::class,
            ReferringDoctorSeeder::class,
            ProcedureSeeder::class,
            InstrumentSeeder::class,
            PatientSeeder::class,
            ExaminationSeeder::class,
        ]);
    }
}
