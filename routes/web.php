<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmployeeController as AdminEmployeeController;
use App\Http\Controllers\Admin\DepartmentController as AdminDepartmentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Manager\ApprovalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Dashboard - accessible by all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Attendance Routes (Department Reps & Managers)
    Route::middleware(['role:user|manager|admin'])->prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/history', [\App\Http\Controllers\HistoryController::class, 'index'])->name('history');
        Route::get('/{attendance}', [\App\Http\Controllers\HistoryController::class, 'show'])->name('show');
        Route::post('/save', [AttendanceController::class, 'store'])->name('save');
        Route::post('/{attendance}/submit', [AttendanceController::class, 'submit'])->name('submit');
    });

    // Manager Routes (Approvals)
    Route::middleware(['role:manager|admin'])->prefix('manager')->name('manager.')->group(function () {
        Route::prefix('approvals')->name('approvals.')->group(function () {
            Route::get('/', [ApprovalController::class, 'index'])->name('index');
            Route::get('/{attendance}', [ApprovalController::class, 'show'])->name('show');
            Route::post('/{attendance}/approve', [ApprovalController::class, 'approve'])->name('approve');
            Route::post('/{attendance}/reject', [ApprovalController::class, 'reject'])->name('reject');
        });
    });

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/reports', [AdminDashboardController::class, 'report'])->name('reports');
        Route::delete('/attendance/{attendance}', [AdminDashboardController::class, 'destroyAttendance'])->name('attendance.destroy');

        // System Settings & Core Identifiers
        Route::get('/settings', [App\Http\Controllers\Admin\SystemSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [App\Http\Controllers\Admin\SystemSettingController::class, 'updateSetting'])->name('settings.update');
        Route::put('/settings/codes/{code}', [App\Http\Controllers\Admin\SystemSettingController::class, 'updateCode'])->name('settings.update-code');

        Route::post('/employees/{employee}/activate', [AdminEmployeeController::class, 'activate'])->name('employees.activate');
        Route::resource('employees', AdminEmployeeController::class);
        Route::resource('departments', AdminDepartmentController::class);
        Route::resource('users', AdminUserController::class);
    });

    // Notification Routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');

    // Profile Routes
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::put('password', [App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');
    });
});
