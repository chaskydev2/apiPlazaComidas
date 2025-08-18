<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StyleController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/style/edit', [StyleController::class, 'edit'])->name('style.edit');
    Route::post('/style/update', [StyleController::class, 'update'])->name('style.update');
});
