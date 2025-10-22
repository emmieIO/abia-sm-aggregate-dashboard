<?php

use App\Http\Controllers\Authentication\AuthenticationController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ParentsController;
use App\Http\Controllers\Dashboard\ReportController;
use App\Http\Controllers\Dashboard\SchoolController;
use App\Http\Controllers\Dashboard\StaffController;
use App\Http\Controllers\Dashboard\StudentController;
use Illuminate\Support\Facades\Route;

// login route
Route::get('/login', [AuthenticationController::class, 'ShowLogin'])->name('login.show')
->middleware('guest');
Route::post('/login', [AuthenticationController::class, 'login'])->name('login')
    ->middleware('throttle:login');

Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/schools', [SchoolController::class, 'index'])->name('schools.index');
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/top-by-subject', [DashboardController::class, 'fetchTopStudentsBySubject'])->name('students.top-by-subject');
    Route::get('/students/low-by-subject', [DashboardController::class, 'fetchLowStudentsBySubject'])->name('students.low-by-subject');
    Route::get('/staffs', [StaffController::class, 'index'])->name('staffs.index');
    Route::get('/parents', [ParentsController::class, 'index'])->name('parents.index');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
});