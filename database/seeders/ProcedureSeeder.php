<?php

namespace Database\Seeders;

use App\Models\Procedure;
use Illuminate\Database\Seeder;

class ProcedureSeeder extends Seeder
{
    public function run(): void
    {
        $procedures = [
            [
                'name' => 'Upper GI Endoscopy (Esophagogastroduodenoscopy)',
                'code' => 'UGIE',
                'description' => 'Diagnostic visual evaluation of the esophagus, stomach, and duodenum.',
                'default_duration' => 20,
                'default_report_template' => "FINDINGS:\nEsophagus: Normal lumen and peristalsis. GE junction at 38cm. Z-line regular without erosion.\nStomach: Fundus, body, incisura, and antrum show healthy pink mucosa. No ulcer or mass.\nDuodenum: D1 bulb and D2 second part visualized with normal mucosal folds.\n\nIMPRESSION:\nNormal Upper Gastrointestinal Endoscopy.",
                'status' => 'Active',
            ],
            [
                'name' => 'Colonoscopy (Total Colon & Terminal Ileum)',
                'code' => 'COL',
                'description' => 'Complete endoscopic examination of rectum, sigmoid, colon, cecum, and terminal ileum.',
                'default_duration' => 35,
                'default_report_template' => "FINDINGS:\nPerianal inspection: Normal.\nScope reached cecum and appendiceal orifice. Terminal ileum intubated and examined.\nBowel preparation: Excellent (Boston Bowel Prep Score 9/9).\nMucosa: Normal vascular pattern throughout the colon. No polyps, diverticula, or bleeding noted.\n\nIMPRESSION:\nComplete Normal Colonoscopy.",
                'status' => 'Active',
            ],
            [
                'name' => 'Diagnostic Bronchoscopy (Flexible)',
                'code' => 'BRON',
                'description' => 'Endoscopic visualization of the vocal cords, trachea, carina, and bronchial tree.',
                'default_duration' => 30,
                'default_report_template' => "FINDINGS:\nVocal Cords: Mobile and symmetric.\nTrachea: Normal caliber and rings.\nCarina: Sharp and centrally located.\nBronchial Tree: Bilateral segmental and subsegmental bronchi inspected. No mucosal lesion, tumor, or active secretion.\n\nIMPRESSION:\nNormal flexible bronchoscopy.",
                'status' => 'Active',
            ],
            [
                'name' => 'Endoscopic Retrograde Cholangiopancreatography',
                'code' => 'ERCP',
                'description' => 'Therapeutic and diagnostic fluoroscopic-endoscopic examination of biliary and pancreatic ducts.',
                'default_duration' => 45,
                'default_report_template' => "FINDINGS:\nMajor papilla cannulated selectively. Cholangiogram shows smooth bile duct without fill defect.\nPancreatic duct: Not opacified.\n\nIMPRESSION:\nSuccessful biliary cannulation without obstruction.",
                'status' => 'Active',
            ],
            [
                'name' => 'Diagnostic Cystoscopy',
                'code' => 'CYST',
                'description' => 'Endoscopic visualization of urethra and urinary bladder.',
                'default_duration' => 20,
                'default_report_template' => "FINDINGS:\nUrethra: Normal.\nBladder: Clear mucosa, normal ureteric orifices with clear efflux bilaterally.\n\nIMPRESSION:\nNormal diagnostic cystoscopy.",
                'status' => 'Active',
            ],
            [
                'name' => 'Diagnostic Endoscopic Ultrasound',
                'code' => 'EUS',
                'description' => 'High-resolution sonographic imaging of GI wall layers and adjacent mediastinal/abdominal organs.',
                'default_duration' => 40,
                'default_report_template' => "FINDINGS:\n5-layer architecture of stomach wall intact. Surrounding lymph nodes normal in size.\n\nIMPRESSION:\nNo submucosal lesion or pathological lymphadenopathy.",
                'status' => 'Active',
            ],
        ];

        foreach ($procedures as $proc) {
            Procedure::firstOrCreate(['code' => $proc['code']], $proc);
        }
    }
}
