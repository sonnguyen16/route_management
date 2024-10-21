<?php
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TuyenDuongController;
use App\Http\Controllers\TaiLieuController;
use App\Http\Controllers\NguoiDungController;
use App\Http\Controllers\DonViController;
use App\Http\Controllers\SuaChuaController;
use App\Http\Controllers\GiamSatController;
use App\Http\Controllers\GioiHanTocDoController;
use App\Http\Controllers\CapPhepController;
use App\Http\Controllers\DuongCamController;
use App\Http\Controllers\CongVanController;
use App\Http\Controllers\BanDoController;

Route::get('/', [AuthController::class, 'index'])->name('index');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');
Route::get('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::prefix('tuyen-duong')->middleware('auth')->group(function () {
    Route::get('/', [TuyenDuongController::class, 'index'])->name('tuyen-duong.index');
    Route::post('/store', [TuyenDuongController::class, 'store'])->name('tuyen-duong.store');
});

Route::prefix('tai-lieu')->middleware('auth')->group(function () {
    Route::delete('/delete', [TaiLieuController::class, 'delete'])->name('tai-lieu.delete');
    Route::post('/store', [TaiLieuController::class, 'store'])->name('tai-lieu.store');
    Route::post('/update-detail', [TaiLieuController::class, 'updateDetail'])->name('tai-lieu.update');
});

Route::prefix('don-vi')->middleware('auth')->group(function () {
    Route::get('/', [DonViController::class, 'index'])->name('don-vi.index');
    Route::post('/store', [DonViController::class, 'store'])->name('don-vi.store');
});

Route::prefix('sua-chua')->middleware('auth')->group(function () {
    Route::get('/', [SuaChuaController::class, 'index'])->name('sua-chua.index');
    Route::post('/store', [SuaChuaController::class, 'store'])->name('sua-chua.store');
});

Route::prefix('cong-van')->middleware('auth')->group(function () {
    Route::get('/', [CongVanController::class, 'index'])->name('cong-van.index');
    Route::post('/store', [CongVanController::class, 'store'])->name('cong-van.store');
});

Route::prefix('gioi-han-toc-do')->middleware('auth')->group(function () {
    Route::get('/', [GioiHanTocDoController::class, 'index'])->name('gioi-han-toc-do.index');
    Route::post('/store', [GioiHanTocDoController::class, 'store'])->name('gioi-han-toc-do.store');
});

Route::prefix('cap-phep')->middleware('auth')->group(function () {
    Route::get('/', [CapPhepController::class, 'index'])->name('cap-phep.index');
    Route::post('/store', [CapPhepController::class, 'store'])->name('cap-phep.store');
});

Route::prefix('giam-sat')->middleware('auth')->group(function () {
    Route::get('/', [GiamSatController::class, 'index'])->name('giam-sat.index');
    Route::post('/store', [GiamSatController::class, 'store'])->name('giam-sat.store');
});

Route::prefix('duong-cam')->middleware('auth')->group(function () {
    Route::get('/', [DuongCamController::class, 'index'])->name('duong-cam.index');
    Route::post('/store', [DuongCamController::class, 'store'])->name('duong-cam.store');
});

Route::prefix('nguoi-dung')->middleware('auth') ->group(function () {
    Route::get('/', [NguoiDungController::class, 'index'])->name('nguoi-dung.index');
    Route::post('/store', [NguoiDungController::class, 'store'])->name('nguoi-dung.store');
});

Route::prefix('map')->middleware('auth')->group(function () {
    Route::get('/', [BanDoController::class, 'index'])->name('map.index');
    Route::post('/update-mota', [BanDoController::class, 'update_mota'])->name('map.update-mota');
});




