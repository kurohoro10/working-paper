<?php

use App\Http\Controllers\ClientWorkingPaperController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkingPaperController;
use App\Http\Controllers\WorkingPaperPdfController;
use Illuminate\Support\Facades\Route;

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
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated Internal Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/working-papers', [WorkingPaperController::class, 'index'])->name('working-papers.index');
    Route::get('/working-papers/create', [WorkingPaperController::class, 'create'])->name('working-papers.create');
    Route::post('/working-papers', [WorkingPaperController::class, 'store'])->name('working-papers.store');
    Route::get('/working-papers/{workingPaper}', [WorkingPaperController::class, 'show'])->name('working-papers.show');

    Route::post(
        '/working-papers/{workingPaper}/finalise',
        [WorkingPaperPdfController::class, 'finalise']
    )->name('working-papers.finalise');

    Route::resource('working-papers', WorkingPaperController::class)->except(['edit', 'update']);
});

/*
|--------------------------------------------------------------------------
| Expenses Routes
|--------------------------------------------------------------------------
*/
Route::get(
    '/working-papers/{workingPaper}/pdf',
    [WorkingPaperPdfController::class, 'download']
);

/*
|--------------------------------------------------------------------------
| Expenses Routes
|--------------------------------------------------------------------------
*/
Route::get(
    '/expenses/{expense}/receipt',
    [ExpenseController::class, 'viewReceipt']
)->name('expenses.receipt');

/*
|--------------------------------------------------------------------------
| Client Access (Signed URLs)
|--------------------------------------------------------------------------
*/
Route::get(
    '/client/working-paper/{token}',
    [ClientWorkingPaperController::class, 'show']
)->name('client.working-paper.show');

Route::post(
    '/client/working-paper/{workingPaper}/expenses',
    [ExpenseController::class, 'store']
)->name('expenses.store');
