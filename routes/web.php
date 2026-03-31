<?php

use App\Http\Controllers\IssueController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChangeRequestController;

Route::middleware(['auth'])->group(function () {

    Route::get('/change-requests', [ChangeRequestController::class,'index']);
    Route::get('/change-requests/create', [ChangeRequestController::class,'create']);
    Route::post('/change-requests', [ChangeRequestController::class,'store']);

});

Route::middleware(['auth'])->group(function () {

    Route::get('/issues', [IssueController::class, 'index']);
    Route::get('/issues/create', [IssueController::class, 'create']);
    Route::post('/issues', [IssueController::class, 'store']);

});

Route::get('/', function () {
    return view('home');
});

Route::middleware(['auth','role:admin'])->group(function () {

    Route::get('/admin-dashboard', function () {
        return "Admin Dashboard";
    });

});

Route::middleware(['auth','role:manager'])->group(function () {

    Route::get('/manager-dashboard', function () {
        return "Manager Dashboard";
    });

});

Route::middleware(['auth','role:engineer'])->group(function () {

    Route::get('/engineer-dashboard', function () {
        return "Engineer Dashboard";
    });

});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/issues/{id}/assign', [IssueController::class,'assignForm']);
    Route::post('/issues/{id}/assign', [IssueController::class,'assign']);

});

Route::get('/issues/{id}/status', [IssueController::class,'statusForm']);
Route::post('/issues/{id}/status', [IssueController::class,'updateStatus']);

Route::get('/issues/{id}', [IssueController::class,'show']);

use App\Http\Controllers\CommentController;

Route::post('/issues/{issue}/comments', [CommentController::class,'store'])
->middleware('auth');

Route::post('/change-requests/{id}/approve',
[ChangeRequestController::class,'approve'])
->middleware('auth');

require __DIR__.'/auth.php';
