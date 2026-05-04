<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemoryController;
use App\Http\Controllers\PublicMemoryController;
use App\Http\Controllers\AccessRequestController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

// Landing page
Route::get('/', function () {
    return auth()->check() ? redirect('/home') : view('welcome');
});
// Route::get('/debug-files', function () {
//     return App\Models\MemoryFile::all(['id','file_path','file_type']);
// });

Route::get('/debug-cloudinary', function () {
    return response()->json(config('cloudinary'));
});

// Public QR routes (no auth needed)
Route::get('/m/{slug}', [PublicMemoryController::class, 'show'])->name('memory.public');
Route::post('/m/{slug}/request-access', [AccessRequestController::class, 'request'])->name('access.request');
Route::post('/m/{slug}/like', [LikeController::class, 'toggle'])->name('memory.like');
Route::post('/m/{slug}/comment', [CommentController::class, 'store'])->name('memory.comment');

// Auth routes
Route::middleware('auth')->group(function () {
    Route::get('/home', [MemoryController::class, 'home'])->name('home');
    Route::get('/memory/create', [MemoryController::class, 'create'])->name('memory.create');
    Route::post('/memory/store', [MemoryController::class, 'store'])->name('memory.store');
    Route::get('/memory/{id}', [MemoryController::class, 'show'])->name('memory.show');
    Route::get('/memory/{id}/edit', [MemoryController::class, 'edit'])->name('memory.edit');
    Route::post('/memory/{id}/update', [MemoryController::class, 'update'])->name('memory.update');
    Route::post('/memory/{id}/delete', [MemoryController::class, 'delete'])->name('memory.delete');

    // Access request management
    Route::get('/dashboard/requests', [AccessRequestController::class, 'dashboard'])->name('access.dashboard');
    Route::post('/access/{id}/approve', [AccessRequestController::class, 'approve'])->name('access.approve');
    Route::post('/access/{id}/reject', [AccessRequestController::class, 'reject'])->name('access.reject');
});

require __DIR__.'/auth.php';