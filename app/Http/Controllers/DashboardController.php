<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Examination;
use App\Models\Patient;
use App\Models\Procedure;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();

        // Key medical stats
        $todayPatientsCount = Patient::whereDate('created_at', $today)->count();
        $todayExaminationsCount = Examination::whereDate('examination_date', $today)->count();
        $pendingReportsCount = Examination::whereIn('status', ['Captured', 'Reporting', 'Reviewed'])
            ->whereDoesntHave('report', function ($q) {
                $q->where('status', 'Finalized');
            })->count();
        $finalizedReportsCount = Report::where('status', 'Finalized')->count();
        $totalPatientsCount = Patient::count();
        $totalExaminationsCount = Examination::count();

        // Recent Examinations list
        $recentExaminations = Examination::with(['patient', 'doctor', 'procedure'])
            ->orderBy('examination_date', 'desc')
            ->orderBy('examination_time', 'desc')
            ->take(8)
            ->get();

        // Pending Reports list (examinations needing report or with draft report)
        $pendingReports = Examination::with(['patient', 'doctor', 'procedure', 'report'])
            ->whereIn('status', ['Captured', 'Reporting', 'Reviewed'])
            ->whereDoesntHave('report', function ($q) {
                $q->where('status', 'Finalized');
            })
            ->orderBy('examination_date', 'desc')
            ->take(6)
            ->get();

        // Procedures breakdown
        $topProcedures = Procedure::withCount('examinations')
            ->orderBy('examinations_count', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'todayPatientsCount',
            'todayExaminationsCount',
            'pendingReportsCount',
            'finalizedReportsCount',
            'totalPatientsCount',
            'totalExaminationsCount',
            'recentExaminations',
            'pendingReports',
            'topProcedures'
        ));
    }
}
