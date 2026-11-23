<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class,'showLogin'])->name('login');
    Route::post('/login', [AuthController::class,'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class,'showRegister'])->name('register');
    Route::post('/register', [AuthController::class,'register'])->name('register.store');
});
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth')->name('logout');
Route::middleware('auth')->group(function (): void {
    Route::get('/reports',[ReportController::class,'index'])->name('reports.index');
    Route::get('/reports/create',[ReportController::class,'create'])->name('reports.create');
    Route::post('/reports',[ReportController::class,'store'])->name('reports.store');
    Route::patch('/reports/{report}/review',[ReportController::class,'review'])->name('reports.review');
});
Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/',[AdminController::class,'index'])->name('index');
    Route::patch('/reports/{report}/status',[AdminController::class,'updateStatus'])->name('reports.status');
});
