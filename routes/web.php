<?php

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\FleetController;

use App\Http\Controllers\Admin\AdminController;

use App\Http\Controllers\Chief\ChiefController;

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

//User Route
Route::middleware(['auth', 'userMiddleware'])->group(function (){

    Route::get('/engineer/dashboard',[UserController::class, 'index'])->name('user.dashboard');

    Route::get('/engineer/fleet', [FleetController::class, 'index'])->name('user.fleet');

    Route::get('/engineer/machineries/{ship_id}', [FleetController::class, 'machineries'])->name('user.machineries');

    // Input Running Hours
    Route::get('/engineer/input-rh/{machinery_id}', [FleetController::class, 'inputRh'])->name('user.input_rh');
    Route::post('/engineer/input-rh/{machinery_id}', [FleetController::class, 'storeRh'])->name('user.store_rh');

    // Job List Maintenance
    Route::get('/engineer/job-list/{machinery_id}', [FleetController::class, 'jobList'])->name('user.job_list');
    Route::post('/engineer/job-complete/{task_id}', [FleetController::class, 'completeTask'])->name('user.complete_task');

    Route::get('/alerts', [FleetController::class, 'alerts'])->name('user.alerts');

    Route::get('/machinery/{machinery_id}/history', [FleetController::class, 'maintenanceHistory'])->name('user.maintenance_history');
});

//Chief Route
Route::middleware(['auth', 'chiefMiddleware'])->group(function (){

    Route::get('/chief/dashboard',[ChiefController::class, 'index'])->name('chief.dashboard');

    Route::post('/chief/verify/{history_id}', [ChiefController::class, 'verifyTask'])->name('chief.verify');
    Route::get('/chief/approvals', [ChiefController::class, 'approvalList'])->name('chief.approvals');
    Route::get('/chief/inspect/{ship_id}', [ChiefController::class, 'inspectVessel'])->name('chief.inspect');
    Route::get('/chief/machinery/{machinery_id}/history', [ChiefController::class, 'machineryHistory'])->name('chief.machinery_history');
});

//Admin Route
Route::middleware(['auth', 'adminMiddleware'])->group(function (){

    Route::get('/admin/dashboard',[AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/vessel/{ship_id}/analyze', [AdminController::class, 'analyzeVessel'])->name('admin.analyze');
    Route::get('/vessel/{ship_id}/export-pdf', [AdminController::class, 'exportPdf'])->name('export_pdf');

});
