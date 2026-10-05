<?php

namespace Database\Seeders;

use App\Models\CapturedImage;
use App\Models\Doctor;
use App\Models\Examination;
use App\Models\Instrument;
use App\Models\Patient;
use App\Models\Procedure;
use App\Models\ReferringDoctor;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExaminationSeeder extends Seeder
{
    public function run(): void
    {
        $creator = User::first();
        $doctor1 = Doctor::first();
        $doctor2 = Doctor::skip(1)->first() ?? $doctor1;
        $refDoc1 = ReferringDoctor::first();
        $refDoc2 = ReferringDoctor::skip(1)->first() ?? $refDoc1;
        $ugie = Procedure::where('code', 'UGIE')->first();
        $col = Procedure::where('code', 'COL')->first();
        $bron = Procedure::where('code', 'BRON')->first();
        $instGastro = Instrument::where('device_type', 'Video Gastroscope')->first();
        $instColo = Instrument::where('device_type', 'Colonovideoscope')->first();

        $patient1 = Patient::where('patient_id', 'P-000001')->first();
        $patient2 = Patient::where('patient_id', 'P-000002')->first();
        $patient3 = Patient::where('patient_id', 'P-000003')->first();
        $patient4 = Patient::where('patient_id', 'P-000004')->first();

        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $lastWeek = Carbon::today()->subDays(7)->format('Y-m-d');

        // Examination 1: Finalized UGIE for Patient 1
        $exam1 = Examination::firstOrCreate(
            ['examination_id' => 'EX-2026-000001'],
            [
                'patient_id' => $patient1->id,
                'procedure_id' => $ugie?->id ?? 1,
                'doctor_id' => $doctor1->id,
                'referring_doctor_id' => $refDoc1->id,
                'instrument_id' => $instGastro?->id ?? 1,
                'indication' => 'Severe reflux symptoms, water brash, and retrosternal discomfort unresponsive to omeprazole 40mg.',
                'examination_date' => $lastWeek,
                'examination_time' => '09:30:00',
                'notes' => 'Procedure carried out under topical lidocaine 10% pharyngeal spray without complications.',
                'status' => 'Finalized',
                'created_by' => $creator?->id,
            ]
        );

        // Add report for Exam 1
        Report::firstOrCreate(
            ['examination_id' => $exam1->id],
            [
                'patient_id' => $patient1->id,
                'doctor_id' => $doctor1->id,
                'report_template_id' => $ugie?->id,
                'findings' => "Esophagus: Mild erythema at lower end above the GE junction (Los Angeles Grade A reflux esophagitis).\nStomach: Healthy mucosa, antrum shows mild superficial gastritis. Biopsy taken for rapid urease test (CLO test).\nDuodenum: Bulb and descending duodenum normal.",
                'impression' => '1. Grade A Reflux Esophagitis.\n2. Mild Antral Gastritis (Biopsy RUT pending).',
                'recommendation' => '1. Continue Esomeprazole 40mg once daily before breakfast for 8 weeks.\n2. Add prokinetic and dietary modifications.\n3. Review with CLO test report.',
                'status' => 'Finalized',
                'finalized_at' => Carbon::parse($lastWeek . ' 10:15:00'),
                'finalized_by' => $creator?->id,
            ]
        );

        // Add sample captured images for Exam 1
        CapturedImage::firstOrCreate(
            ['examination_id' => $exam1->id, 'sequence' => 1],
            [
                'patient_id' => $patient1->id,
                'filename' => 'exam1_ge_junction.jpg',
                'path' => 'captures/samples/ge_junction.jpg',
                'sequence' => 1,
                'caption' => 'GE junction showing Los Angeles Grade A mucosal breaks',
                'captured_at' => Carbon::parse($lastWeek . ' 09:36:12'),
                'captured_by' => $creator?->id,
            ]
        );
        CapturedImage::firstOrCreate(
            ['examination_id' => $exam1->id, 'sequence' => 2],
            [
                'patient_id' => $patient1->id,
                'filename' => 'exam1_stomach_antrum.jpg',
                'path' => 'captures/samples/stomach_antrum.jpg',
                'sequence' => 2,
                'caption' => 'Antral mucosa showing focal hyperemic streaks',
                'captured_at' => Carbon::parse($lastWeek . ' 09:38:45'),
                'captured_by' => $creator?->id,
            ]
        );

        // Examination 2: Finalized Colonoscopy for Patient 2
        $exam2 = Examination::firstOrCreate(
            ['examination_id' => 'EX-2026-000002'],
            [
                'patient_id' => $patient2->id,
                'procedure_id' => $col?->id ?? 2,
                'doctor_id' => $doctor1->id,
                'referring_doctor_id' => $refDoc2->id,
                'instrument_id' => $instColo?->id ?? 2,
                'indication' => 'Screening colonoscopy in 61-year-old male with positive fecal occult blood test (FOBT).',
                'examination_date' => $yesterday,
                'examination_time' => '11:00:00',
                'notes' => 'Cecum reached in 7 minutes. Withdrawal time 11 minutes with meticulous mucosal inspection.',
                'status' => 'Finalized',
                'created_by' => $creator?->id,
            ]
        );

        Report::firstOrCreate(
            ['examination_id' => $exam2->id],
            [
                'patient_id' => $patient2->id,
                'doctor_id' => $doctor1->id,
                'report_template_id' => $col?->id,
                'findings' => "Bowel preparation adequate (BBPS 8/9).\nRectum & Sigmoid: Normal mucosa.\nDescending & Transverse Colon: A 6mm sessile polyp was detected in mid-transverse colon. Cold snare polypectomy successfully performed. No immediate hemorrhage.\nAscending Colon & Cecum: Normal landmark visualized including ileocecal valve and appendiceal orifice.",
                'impression' => 'Transverse colon sessile polyp (6mm) - Polypectomy completed without complication.',
                'recommendation' => '1. Histopathology result of excised polyp in 5-7 days.\n2. Surveillance colonoscopy recommended in 3 to 5 years depending on histology.',
                'status' => 'Finalized',
                'finalized_at' => Carbon::parse($yesterday . ' 12:00:00'),
                'finalized_by' => $creator?->id,
            ]
        );

        // Examination 3: Today's In Progress / Reporting for Patient 3
        $exam3 = Examination::firstOrCreate(
            ['examination_id' => 'EX-2026-000003'],
            [
                'patient_id' => $patient3->id,
                'procedure_id' => $ugie?->id ?? 1,
                'doctor_id' => $doctor2->id,
                'referring_doctor_id' => $refDoc1->id,
                'instrument_id' => $instGastro?->id ?? 1,
                'indication' => 'Early satiety, bloating, and postprandial fullness for 6 months.',
                'examination_date' => $today,
                'examination_time' => '10:00:00',
                'notes' => 'Endoscopic images captured. Awaiting report transcription.',
                'status' => 'Reporting',
                'created_by' => $creator?->id,
            ]
        );

        // Examination 4: Today's Scheduled for Patient 4
        Examination::firstOrCreate(
            ['examination_id' => 'EX-2026-000004'],
            [
                'patient_id' => $patient4->id,
                'procedure_id' => $bron?->id ?? 3,
                'doctor_id' => $doctor2->id,
                'referring_doctor_id' => $refDoc2->id,
                'instrument_id' => null,
                'indication' => 'Evaluation of chronic hemoptysis and subsegmental bronchial lesion.',
                'examination_date' => $today,
                'examination_time' => '14:30:00',
                'notes' => 'Patient premedicated. Fasting verified.',
                'status' => 'Scheduled',
                'created_by' => $creator?->id,
            ]
        );

        // Examination 5: Second historical examination for Patient 1 (Upper GI follow-up scheduled)
        Examination::firstOrCreate(
            ['examination_id' => 'EX-2026-000005'],
            [
                'patient_id' => $patient1->id,
                'procedure_id' => $ugie?->id ?? 1,
                'doctor_id' => $doctor1->id,
                'referring_doctor_id' => $refDoc1->id,
                'instrument_id' => $instGastro?->id ?? 1,
                'indication' => 'Follow-up endoscopy to reassess reflux esophagitis healing after 8-week therapy.',
                'examination_date' => $today,
                'examination_time' => '15:15:00',
                'notes' => 'Scheduled follow up examination.',
                'status' => 'In Progress',
                'created_by' => $creator?->id,
            ]
        );
    }
}
