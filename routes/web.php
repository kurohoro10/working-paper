<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ClientWorkingPaperController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkingPaperController;
use App\Http\Controllers\WorkingPaperPdfController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth', 'verified')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

    // User profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Internal working papers
    Route::controller(WorkingPaperController::class)->group(function () {
        // Route::get('/working-papers', 'index')->name('working-papers.index');
        // Route::get('/working-papers/create', 'create')->name('working-papers.create');
        // Route::post('/working-papers', 'store')->name('working-papers.store');
        // Route::get('/working-papers/{workingPaper}', 'show')->name('working-papers.show');

        Route::post('/working-papers/{workingPaper}/finalise', 'finalise')->name('working-papers.finalise');

        // Handle all standard CRUD except edit/update
        Route::resource('working-papers', WorkingPaperController::class)->except(['edit', 'update']);
    });

    // Audit logs
    Route::get('/admin/audit-logs', [AuditLogController::class, 'index'])->middleware('can:viewAuditLogs');
});

/*
|--------------------------------------------------------------------------
| Expenses Routes
|--------------------------------------------------------------------------
*/
Route::controller(ExpenseController::class)->group(function () {

    Route::get('/expenses/{expense}/receipt', 'viewReceipt')->name('expenses.receipt');

    Route::post('/client/working-paper/{workingPaper}/expenses', 'store')->name('expenses.store');
});

/*
|--------------------------------------------------------------------------
| Client Access (Signed URLs)
|--------------------------------------------------------------------------
*/
Route::get('/client/working-paper/{token}', [ClientWorkingPaperController::class, 'show'])->name('client.working-paper.show');

/*
|--------------------------------------------------------------------------
| PDF Controller
|--------------------------------------------------------------------------
*/
Route::get('/working-papers/{workingPaper}/pdf', [WorkingPaperPdfController::class, 'download']);

require __DIR__.'/auth.php';
