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

Route::get('/', [AuthController::class, 'index'])->name('index');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::prefix('tuyen-duong')->group(function () {
    Route::get('/', [TuyenDuongController::class, 'index'])->name('tuyen-duong.index');
    Route::post('/store', [TuyenDuongController::class, 'store'])->name('tuyen-duong.store');
})->middleware('auth');

Route::prefix('tai-lieu')->group(function () {
    Route::get('/', [TaiLieuController::class, 'index'])->name('tai-lieu.index');
    Route::post('/store', [TaiLieuController::class, 'store'])->name('tai-lieu.store');
})->middleware('auth');

Route::prefix('don-vi')->group(function () {
    Route::get('/', [DonViController::class, 'index'])->name('don-vi.index');
    Route::post('/store', [DonViController::class, 'store'])->name('don-vi.store');
})->middleware('auth');

Route::prefix('sua-chua')->group(function () {
    Route::get('/', [SuaChuaController::class, 'index'])->name('sua-chua.index');
    Route::post('/store', [SuaChuaController::class, 'store'])->name('sua-chua.store');
})->middleware('auth');

Route::prefix('cong-van')->group(function () {
    Route::get('/', [CongVanController::class, 'index'])->name('cong-van.index');
    Route::post('/store', [CongVanController::class, 'store'])->name('cong-van.store');
})->middleware('auth');

Route::prefix('gioi-han-toc-do')->group(function () {
    Route::get('/', [GioiHanTocDoController::class, 'index'])->name('gioi-han-toc-do.index');
    Route::post('/store', [GioiHanTocDoController::class, 'store'])->name('gioi-han-toc-do.store');
})->middleware('auth');

Route::prefix('cap-phep')->group(function () {
    Route::get('/', [CapPhepController::class, 'index'])->name('cap-phep.index');
    Route::post('/store', [CapPhepController::class, 'store'])->name('cap-phep.store');
})->middleware('auth');

Route::prefix('giam-sat')->group(function () {
    Route::get('/', [GiamSatController::class, 'index'])->name('giam-sat.index');
    Route::post('/store', [GiamSatController::class, 'store'])->name('giam-sat.store');
})->middleware('auth');

Route::prefix('duong-cam')->group(function () {
    Route::get('/', [DuongCamController::class, 'index'])->name('duong-cam.index');
    Route::post('/store', [DuongCamController::class, 'store'])->name('duong-cam.store');
})->middleware('auth');

Route::prefix('nguoi-dung')->group(function () {
    Route::get('/', [NguoiDungController::class, 'index'])->name('nguoi-dung.index');
    Route::post('/store', [NguoiDungController::class, 'store'])->name('nguoi-dung.store');
})->middleware('auth');

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});


Route::get('/map', function () {
    return Inertia::render('Map/Index');
})->name('map');


