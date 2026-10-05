<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\Examination;
use App\Models\Patient;
use App\Models\Procedure;
use App\Models\User;
use Tests\TestCase;

class ExaminationWorkflowTest extends TestCase
{
    public function test_examination_id_is_auto_generated_with_format_ex_year_000001(): void
    {
        $admin = User::where('email', 'admin@hospital.com')->first();
        $patient = Patient::first();
        $doctor = Doctor::first();
        $procedure = Procedure::first();

        $response = $this->actingAs($admin)->post('/examinations', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'procedure_id' => $procedure->id,
            'indication' => 'Test procedural indication for endoscopy exam.',
            'examination_date' => date('Y-m-d'),
            'examination_time' => '10:00',
            'status' => 'Scheduled',
        ]);

        $year = date('Y');
        $this->assertDatabaseHas('examinations', [
            'patient_id' => $patient->id,
            'procedure_id' => $procedure->id,
        ]);

        $exam = Examination::where('patient_id', $patient->id)->latest('id')->first();
        $this->assertMatchesRegularExpression("/^EX-{$year}-\\d{6}$/", $exam->examination_id);
    }

    public function test_examination_status_can_be_advanced(): void
    {
        $admin = User::where('email', 'admin@hospital.com')->first();
        $exam = Examination::first();

        $response = $this->actingAs($admin)->patch("/examinations/{$exam->id}/status", [
            'status' => 'In Progress',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('In Progress', $exam->fresh()->status);
    }

    public function test_report_can_be_written_and_finalized(): void
    {
        $admin = User::where('email', 'admin@hospital.com')->first();
        $exam = Examination::where('status', '!=', 'Finalized')->first();

        $response = $this->actingAs($admin)->put("/examinations/{$exam->id}/report", [
            'doctor_id' => $exam->doctor_id,
            'findings' => 'Normal stomach antrum and duodenal folds observed.',
            'impression' => 'Normal endoscopic study.',
            'recommendation' => 'No further intervention required.',
            'status' => 'Finalized',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('Finalized', $exam->fresh()->status);
        $this->assertEquals('Finalized', $exam->fresh()->report->status);
    }
}
