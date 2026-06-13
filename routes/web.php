<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\MasterClassController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/category/{id}', [PageController::class, 'category'])->name('category.show');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/cabinet', [CabinetController::class, 'index'])->name('cabinet');

    Route::get('/master-classes/create', [MasterClassController::class, 'create'])->name('master-class.create');
    Route::post('/master-classes', [MasterClassController::class, 'store'])->name('master-class.store');
    Route::get('/api/occupied-slots', [MasterClassController::class, 'getOccupiedSlots']);

    Route::get('/master-classes/{id}/edit', [MasterClassController::class, 'edit'])->name('master-class.edit');
    Route::put('/master-classes/{id}', [MasterClassController::class, 'update'])->name('master-class.update');

    Route::get('/enroll/{id}/confirm', [EnrollmentController::class, 'confirm'])->name('enroll.confirm');
    Route::post('/enroll/{id}', [EnrollmentController::class, 'store'])->name('enroll.store');
});
