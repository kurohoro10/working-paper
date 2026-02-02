<?php

use App\Http\Controllers\Admin\AdminClientController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ClientWorkingPaperController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\WorkingPaperController;
use App\Http\Controllers\WorkingPaperImportExportController;
use App\Http\Controllers\WorkingPaperPdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('auth', 'verified')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

    // Register user via admin
    Route::middleware('can:create,App\Models\User')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('clients', AdminClientController::class);
    });

    // User profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Add User & Edit User - Restricted to Admin/Internal
    Route::middleware('can:create,App\Models\User')->prefix('admin')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Internal working papers
    Route::controller(WorkingPaperController::class)->group(function () {
        Route::post('/working-papers/{workingPaper}/finalise', 'finalise')->name('working-papers.finalise');
    });

    // Import and Export
    Route::controller(WorkingPaperImportExportController::class)->group(function () {
        Route::get('/working-papers/export', 'export')->middleware('can:admin')->name('working-papers.export');
        Route::get('/working-papers/import', 'showImportForm')->middleware('can:admin')->name('working-papers.import');
        Route::post('/working-papers/import/preview', 'storePreview')->middleware('can:admin')->name('working-paper.import.preview.store');
        Route::get('/working-papers/import/preview', 'showPreview')->middleware('can:admin')->name('working-paper.import.preview.show');
        Route::post('/working-papers/import/execute', 'execute')->middleware('can:admin')->name('working-paper.import.execute');
    });

    // Handle all standard CRUD except edit/update
    Route::resource('working-papers', WorkingPaperController::class);

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

    Route::resource('expenses', ExpenseController::class)->only(['edit', 'update', 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Client Access (Token)
|--------------------------------------------------------------------------
*/
// Token generation
Route::post('/working-papers/{workingPaper}/share-token/regenerate', [TokenController::class, 'regenerateShareToken'])->name('working-papers.share-token.regenerate');

Route::get('/client/working-paper/{token}', [ClientWorkingPaperController::class, 'show'])->name('client.working-paper.show');

/*
|--------------------------------------------------------------------------
| PDF Controller
|--------------------------------------------------------------------------
*/
Route::get('/working-papers/{workingPaper}/pdf', [WorkingPaperPdfController::class, 'download']);

require __DIR__.'/auth.php';
