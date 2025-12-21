<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FinalReportFileController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MajorController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\InternshipAssignmentController;
use App\Http\Controllers\Admin\IndustryController as AdminIndustryController;
use App\Http\Controllers\Admin\FinalGradeReportController;
use App\Http\Controllers\Teacher\InternshipVerificationController;
use App\Http\Controllers\Teacher\MonitoringController;
use App\Http\Controllers\Teacher\FinalGradeController as TeacherFinalGradeController;
use App\Http\Controllers\Teacher\LogbookReviewController;
use App\Http\Controllers\Industry\IndustryProfileController;
use App\Http\Controllers\Industry\QuotaController;
use App\Http\Controllers\Industry\InternshipConfirmationController;
use App\Http\Controllers\Industry\LogbookValidationController;
use App\Http\Controllers\Industry\StudentAssessmentController;
use App\Http\Controllers\Student\IndustryController as StudentIndustryController;
use App\Http\Controllers\Student\InternshipApplicationController as StudentApplicationController;
use App\Http\Controllers\Student\LogbookController as StudentLogbookController;
use App\Http\Controllers\Student\FinalReportController as StudentFinalReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/final-reports/{report}/file', [FinalReportFileController::class, 'show'])
        ->name('final_reports.file');

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');
});

require __DIR__.'/auth.php';

// Group Admin
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // Resource Majors
        Route::resource('majors', MajorController::class)->except(['show']);

        Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
        Route::patch('users/{user}/status', [UserManagementController::class, 'updateStatus'])
            ->name('users.update-status');

        Route::get('applications', [InternshipAssignmentController::class, 'index'])
            ->name('applications.index');

        Route::get('applications/{application}/assign', [InternshipAssignmentController::class, 'assignForm'])
            ->name('applications.assignForm');

        Route::post('applications/{application}/assign', [InternshipAssignmentController::class, 'assign'])
            ->name('applications.assign');

        // daftar industri + verifikasi
        Route::get('industries', [AdminIndustryController::class, 'index'])
            ->name('industries.index');

        Route::get('industries/{industry}', [AdminIndustryController::class, 'show'])
            ->name('industries.show');

        Route::patch('industries/{industry}/status', [AdminIndustryController::class, 'updateStatus'])
            ->name('industries.update-status');

        Route::get('/final-grades', [FinalGradeReportController::class, 'index'])
            ->name('final_grades.index');

        Route::get('/final-grades/export-csv', [FinalGradeReportController::class, 'exportCsv'])
            ->name('final_grades.export_csv');

        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    });

// Group Guru Pembimbing
Route::middleware(['auth', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Teacher\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('applications', [InternshipVerificationController::class, 'index'])->name('applications.index');

        Route::get('applications/{application}', [InternshipVerificationController::class, 'show'])->name('applications.show');

        Route::post('applications/{application}/approve', [InternshipVerificationController::class, 'approve'])
            ->name('applications.approve');

        // Route::post('applications/{application}/revision', [InternshipVerificationController::class, 'revision'])
        //     ->name('applications.revision');

        Route::delete('/applications/{application}', [InternshipVerificationController::class, 'destroy'])
            ->name('applications.destroy');

        Route::put('/applications/{application}', [InternshipVerificationController::class, 'update'])
            ->name('applications.update');

        Route::get('/monitoring', [MonitoringController::class, 'index'])
            ->name('monitoring.index');

        Route::get('/monitoring/{application}', [MonitoringController::class, 'show'])
            ->name('monitoring.show');

        Route::get('/logbooks/{logbookEntry}', [LogbookReviewController::class, 'show'])
            ->name('logbooks.show');

        Route::get('/logbooks/{logbookEntry}/documentation', [LogbookReviewController::class, 'documentation'])
            ->name('logbooks.documentation');

        Route::post('/monitoring/{application}/notes', [MonitoringController::class, 'storeNote'])
            ->name('monitoring.notes.store');

        Route::get('/final-grades', [TeacherFinalGradeController::class, 'index'])
            ->name('final_grades.index');

        Route::get('/final-grades/{application}/edit', [TeacherFinalGradeController::class, 'edit'])
            ->name('final_grades.edit');

        Route::post('/final-grades/{application}', [TeacherFinalGradeController::class, 'update'])
            ->name('final_grades.update');
    });

// Group Pembimbing Lapangan / Industri
Route::middleware(['auth', 'role:industry_supervisor'])
    ->prefix('industry')
    ->name('industry.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Industry\DashboardController::class, 'index'])
            ->name('dashboard');

        // Profil Industri
        Route::get('profile', [IndustryProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [IndustryProfileController::class, 'update'])->name('profile.update');

        Route::resource('quotas', QuotaController::class);

        Route::get('applications', [InternshipConfirmationController::class, 'index'])
            ->name('applications.index');

        Route::post('applications/{application}/confirm', [InternshipConfirmationController::class, 'confirm'])
            ->name('applications.confirm');

        Route::get('/logbooks', [LogbookValidationController::class, 'index'])
            ->name('logbooks.index');

        Route::get('/logbooks/{logbook}', [LogbookValidationController::class, 'show'])
            ->name('logbooks.show');

        Route::post('/logbooks/{logbook}/validate', [LogbookValidationController::class, 'validateEntry'])
            ->name('logbooks.validate');

        Route::get('/logbooks/{logbook}/evidence', [LogbookValidationController::class, 'showEvidence'])
            ->name('logbooks.evidence');

        Route::get('/assessments', [StudentAssessmentController::class, 'index'])
            ->name('assessments.index');

        Route::get('/assessments/{application}/edit', [StudentAssessmentController::class, 'edit'])
            ->name('assessments.edit');

        Route::post('/assessments/{application}', [StudentAssessmentController::class, 'update'])
            ->name('assessments.update');
    });

// Group Siswa
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('industries', [StudentIndustryController::class, 'index'])
            ->name('industries.index');

         // ✅ Daftar industri yang bisa dipilih siswa
        Route::get('/industries', [StudentIndustryController::class, 'index'])
            ->name('industries.index');

        // ✅ Daftar pengajuan prakerin siswa
        Route::get('/applications', [StudentApplicationController::class, 'index'])
            ->name('applications.index');

        // ✅ Form pengajuan prakerin
        Route::get('/applications/create', [StudentApplicationController::class, 'create'])
            ->name('applications.create');

        // ✅ Simpan pengajuan prakerin
        Route::post('/applications', [StudentApplicationController::class, 'store'])
            ->name('applications.store');

        // Logbook Routes
        Route::get('/logbooks', [StudentLogbookController::class, 'index'])
            ->name('logbooks.index');

        Route::get('/logbooks/create', [StudentLogbookController::class, 'create'])
            ->name('logbooks.create');

        Route::post('/logbooks', [StudentLogbookController::class, 'store'])
            ->name('logbooks.store');

        Route::get('/final-report', [StudentFinalReportController::class, 'index'])
            ->name('final_report.index');

        Route::post('/final-report', [StudentFinalReportController::class, 'store'])
            ->name('final_report.store');

        Route::put('/final-report', [StudentFinalReportController::class, 'update'])
            ->name('final_report.update');
    });
