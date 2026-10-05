<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ExaminationController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\OrganizationSettingController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientHistoryController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\ReferringDoctorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportTemplateController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated Medical Workstation Routes
Route::middleware(['auth', 'active'])->group(function () {
    // Root redirect
    Route::get('/', fn () => redirect()->route('dashboard'));

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User Profile & Password
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/password', [AuthController::class, 'showPasswordForm'])->name('profile.password');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password.update');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patient Management
    Route::middleware('permission:patient.view')->group(function () {
        Route::resource('patients', PatientController::class);
        Route::get('/patient-history', [PatientHistoryController::class, 'index'])->name('patient-history.index');
    });

    // Medical Management
    Route::middleware('permission:doctor.view')->group(function () {
        Route::resource('doctors', DoctorController::class);
    });

    Route::middleware('permission:referring_doctor.view')->group(function () {
        Route::resource('referring-doctors', ReferringDoctorController::class);
    });

    Route::middleware('permission:procedure.view')->group(function () {
        Route::resource('procedures', ProcedureController::class);
    });

    Route::middleware('permission:instrument.view')->group(function () {
        Route::resource('instruments', InstrumentController::class);
    });

    // Examination Management
    Route::middleware('permission:examination.view')->group(function () {
        Route::resource('examinations', ExaminationController::class);
        Route::patch('/examinations/{examination}/status', [ExaminationController::class, 'updateStatus'])->name('examinations.status');
    });

    // Reporting
    Route::middleware('permission:report.view')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
        Route::get('/examinations/{examination}/report', [ReportController::class, 'edit'])->name('reports.edit');
        Route::put('/examinations/{examination}/report', [ReportController::class, 'update'])->name('reports.update');

        Route::get('/report-templates', [ReportTemplateController::class, 'index'])->name('report-templates.index');
        Route::put('/report-templates/{procedure}', [ReportTemplateController::class, 'update'])->name('report-templates.update');
    });

    // Administration
    Route::middleware('permission:user.view')->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::middleware('permission:role.view')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    });

    Route::middleware('permission:setting.view')->group(function () {
        Route::get('/settings', [OrganizationSettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [OrganizationSettingController::class, 'update'])->name('settings.update');
    });

    Route::middleware('permission:audit.view')->group(function () {
        Route::get('/audit', [AuditLogController::class, 'index'])->name('audit.index');
    });
});
