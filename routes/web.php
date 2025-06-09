<?php

use App\Http\Controllers\{
    CalendarController,
    DashboardController,
    InsightController,
    ProfileController,
    TransactionController
};

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    //Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    //Transactions Resourceful Route
    Route::resource('transactions', TransactionController::class);
    
    //Calendar Route
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    
    //Insight Route
    Route::get('/insights', [InsightController::class, 'index'])->name('insights');
    
    //Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';