<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TuyenDuongController;
use App\Http\Controllers\TaiLieuController;
use App\Http\Controllers\NguoiDungController;
use Inertia\Inertia;

Route::get('/', [AuthController::class, 'index'])->name('auth.index');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/logout', [AuthController::class, 'destroy'])->name('auth.logout');

Route::prefix('tuyen-duong')->group(function () {
    Route::get('/', [TuyenDuongController::class, 'index'])->name('tuyen-duong.index');
    Route::post('/store', [TuyenDuongController::class, 'store'])->name('tuyen-duong.store');
})->middleware('auth');

Route::prefix('tai-lieu')->group(function () {
    Route::get('/', [TaiLieuController::class, 'index'])->name('tai-lieu.index');
    Route::post('/store', [TaiLieuController::class, 'store'])->name('tai-lieu.store');
})->middleware('auth');

Route::prefix('nguoi-dung')->group(function () {
    Route::get('/', [NguoiDungController::class, 'index'])->name('nguoi-dung.index');
    Route::post('/store', [NguoiDungController::class, 'store'])->name('nguoi-dung.store');
})->middleware('auth');


Route::get('/map', function () {
    return Inertia::render('Map/Index');
})->name('map');


