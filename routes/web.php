<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DailyUpdateController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Users Management
    Route::group(['prefix' => 'users', 'middleware' => ['role:superadmin|admin']], function () {
        Route::resource('admin', AdminController::class);
        Route::resource('employee', EmployeeController::class);
    });

    // Client Management
    Route::resource('client', ClientController::class);

    // Timecard System
    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class);
    Route::get('tasks/attachments/{attachment}/download', [TaskController::class, 'downloadAttachment'])->name('tasks.attachments.download');
    Route::get('tasks/attachments/{attachment}/view', [TaskController::class, 'viewAttachment'])->name('tasks.attachments.view');
    Route::delete('tasks/attachments/{attachment}', [TaskController::class, 'deleteAttachment'])->name('tasks.attachments.destroy');
    Route::get('daily-updates/fetch-by-date', [DailyUpdateController::class, 'fetchByDate'])->name('daily-updates.fetch-by-date');
    Route::get('daily-updates/fetch-tasks/{projectId}', [DailyUpdateController::class, 'fetchTasksByProject'])->name('daily-updates.fetch-tasks');
    Route::resource('daily-updates', DailyUpdateController::class);

    // Reports (Admin/Superadmin only - controlled in controller or via route group)
    Route::group(['prefix' => 'reports', 'middleware' => ['role:superadmin|admin']], function () {
        Route::get('employees', [App\Http\Controllers\ReportController::class, 'employeeReports'])->name('reports.employees');
        Route::get('employees/export', [App\Http\Controllers\ReportController::class, 'exportEmployeeReports'])->name('reports.employees.export');
        Route::get('tasks', [App\Http\Controllers\ReportController::class, 'employeeTasks'])->name('reports.tasks');
    });
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
