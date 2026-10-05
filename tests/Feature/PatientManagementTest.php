<?php

namespace Tests\Feature;

use App\Models\Examination;
use App\Models\Patient;
use App\Models\User;
use Tests\TestCase;

class PatientManagementTest extends TestCase
{
    public function test_patient_id_is_auto_generated_with_format_p_000001(): void
    {
        $patient = Patient::create([
            'name' => 'Auto ID Test Patient',
            'gender' => 'Male',
            'phone' => '+1 (555) 909-8800',
            'status' => 'Active',
        ]);

        $this->assertNotNull($patient->patient_id);
        $this->assertMatchesRegularExpression('/^P-\d{6}$/', $patient->patient_id);
    }

    public function test_patient_with_medical_history_cannot_be_deleted(): void
    {
        $admin = User::where('email', 'admin@hospital.com')->first();
        $patientWithExam = Patient::whereHas('examinations')->first();

        $this->assertNotNull($patientWithExam);
        $this->assertTrue($patientWithExam->hasMedicalHistory());

        $response = $this->actingAs($admin)->delete("/patients/{$patientWithExam->id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('patients', ['id' => $patientWithExam->id]);
    }

    public function test_patient_profile_renders_all_required_tabs(): void
    {
        $admin = User::where('email', 'admin@hospital.com')->first();
        $patient = Patient::whereHas('examinations')->first();

        $response = $this->actingAs($admin)->get("/patients/{$patient->id}");

        $response->assertStatus(200);
        $response->assertSee($patient->name);
        $response->assertSee($patient->patient_id);
        $response->assertSee('Overview');
        $response->assertSee('Examinations');
        $response->assertSee('Reports');
        $response->assertSee('Images');
        $response->assertSee('Videos');
        $response->assertSee('History Timeline');
    }

    public function test_patient_search_and_filters_work(): void
    {
        $admin = User::where('email', 'admin@hospital.com')->first();
        $patient = Patient::first();

        $response = $this->actingAs($admin)->get('/patients?search=' . urlencode($patient->name));
        $response->assertStatus(200);
        $response->assertSee($patient->patient_id);
    }
}
