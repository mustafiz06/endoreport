<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $selectedPatient = null;
        $historyExaminations = collect();

        if ($patientId = $request->input('patient_id')) {
            $selectedPatient = Patient::with([
                'examinations.procedure',
                'examinations.doctor',
                'examinations.referringDoctor',
                'examinations.instrument',
                'examinations.report.doctor',
                'examinations.images',
                'examinations.videos',
            ])->find($patientId);

            if ($selectedPatient) {
                $historyExaminations = $selectedPatient->examinations;
            }
        }

        // Quick patient list for search
        $patients = Patient::withCount('examinations')
            ->orderBy('name')
            ->get();

        return view('patient-history.index', compact('selectedPatient', 'historyExaminations', 'patients'));
    }
}
