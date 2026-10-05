<?php

namespace Database\Seeders;

use App\Models\Instrument;
use Illuminate\Database\Seeder;

class InstrumentSeeder extends Seeder
{
    public function run(): void
    {
        $instruments = [
            [
                'name' => 'Olympus EVIS EXERA III Video Gastroscope GIF-HQ190',
                'device_type' => 'Video Gastroscope',
                'manufacturer' => 'Olympus Medical Systems',
                'model' => 'GIF-HQ190',
                'serial_number' => 'SN-GIF-299104',
                'department' => 'Endoscopy Suite A',
                'status' => 'Active',
            ],
            [
                'name' => 'Olympus EVIS LUCERA ELITE Colonovideoscope CF-HQ290I',
                'device_type' => 'Colonovideoscope',
                'manufacturer' => 'Olympus Medical Systems',
                'model' => 'CF-HQ290I',
                'serial_number' => 'SN-COL-884012',
                'department' => 'Endoscopy Suite B',
                'status' => 'Active',
            ],
            [
                'name' => 'Pentax Medical Video Processor & Light Source EPK-i7010',
                'device_type' => 'Video Processor',
                'manufacturer' => 'Pentax Medical',
                'model' => 'EPK-i7010 (OPTIMISTA)',
                'serial_number' => 'SN-PRC-551029',
                'department' => 'Endoscopy Suite A',
                'status' => 'Active',
            ],
            [
                'name' => 'Fujifilm ELUXEO 7000 Video Endoscopy System',
                'device_type' => 'Multi-Light Video System',
                'manufacturer' => 'Fujifilm Healthcare',
                'model' => 'VP-7000 / BL-7000',
                'serial_number' => 'SN-FUJ-992011',
                'department' => 'Endoscopy Suite C',
                'status' => 'Active',
            ],
            [
                'name' => 'Karl Storz Tele Pack+ Compact Endoscopy Unit',
                'device_type' => 'Compact Video Unit',
                'manufacturer' => 'Karl Storz SE & Co.',
                'model' => 'TP-100+',
                'serial_number' => 'SN-KS-331002',
                'department' => 'Procedure Room 1',
                'status' => 'Maintenance',
            ],
        ];

        foreach ($instruments as $inst) {
            Instrument::firstOrCreate(['serial_number' => $inst['serial_number']], $inst);
        }
    }
}
