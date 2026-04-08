<?php

use App\Http\Controllers\ChangeRequestController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/open', [NotificationController::class, 'open'])->name('notifications.open');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('role:admin,manager,analyst');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export')->middleware('role:admin,manager,analyst');

    Route::resource('issues', IssueController::class);
    Route::get('/issues/{issue}/assign', [IssueController::class, 'assignForm'])->name('issues.assign.form')->middleware('role:admin,manager');
    Route::post('/issues/{issue}/assign', [IssueController::class, 'assign'])->name('issues.assign')->middleware('role:admin,manager');
    Route::get('/issues/{issue}/status', [IssueController::class, 'statusForm'])->name('issues.status.form');
    Route::patch('/issues/{issue}/status', [IssueController::class, 'updateStatus'])->name('issues.status.update');
    Route::post('/issues/{issue}/comments', [CommentController::class, 'store'])->name('issues.comments.store');

    Route::resource('change-requests', ChangeRequestController::class);
    Route::post('/change-requests/{changeRequest}/approve', [ChangeRequestController::class, 'approve'])->name('change-requests.approve');
    Route::get('/change-requests/{changeRequest}/schedule', [ChangeRequestController::class, 'scheduleForm'])->name('change-requests.schedule.form')->middleware('role:admin');
    Route::patch('/change-requests/{changeRequest}/schedule', [ChangeRequestController::class, 'schedule'])->name('change-requests.schedule')->middleware('role:admin');
    Route::post('/change-requests/{changeRequest}/verify', [ChangeRequestController::class, 'verify'])->name('change-requests.verify')->middleware('role:engineer');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
