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
use App\Http\Controllers\CauHinhController;
use App\Http\Controllers\DiemTaiNanController;
use App\Http\Controllers\DenGiaoThongController;
use App\Http\Controllers\CauController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

Route::get('/', [AuthController::class, 'index'])->name('index');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');
Route::get('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::prefix('tuyen-duong')->middleware('auth')->group(function () {
    Route::get('/', [TuyenDuongController::class, 'index'])->name('tuyen-duong.index');
    Route::post('/store', [TuyenDuongController::class, 'store'])->name('tuyen-duong.store');
    Route::get('/delete/{id}', [TuyenDuongController::class, 'delete'])->name('tuyen-duong.delete');
});

Route::prefix('tai-lieu')->middleware('auth')->group(function () {
    Route::delete('/delete', [TaiLieuController::class, 'delete'])->name('tai-lieu.delete');
    Route::post('/store', [TaiLieuController::class, 'store'])->name('tai-lieu.store');
    Route::post('/update-detail', [TaiLieuController::class, 'updateDetail'])->name('tai-lieu.update');
});

Route::prefix('don-vi')->middleware('auth')->group(function () {
    Route::get('/', [DonViController::class, 'index'])->name('don-vi.index');
    Route::post('/store', [DonViController::class, 'store'])->name('don-vi.store');
    Route::get('/delete/{id}', [DonViController::class, 'delete'])->name('don-vi.delete');
});

Route::prefix('sua-chua')->middleware('auth')->group(function () {
    Route::get('/', [SuaChuaController::class, 'index'])->name('sua-chua.index');
    Route::post('/store', [SuaChuaController::class, 'store'])->name('sua-chua.store');
    Route::get('/delete/{id}', [SuaChuaController::class, 'delete'])->name('sua-chua.delete');
});

Route::prefix('cong-van')->middleware('auth')->group(function () {
    Route::get('/', [CongVanController::class, 'index'])->name('cong-van.index');
    Route::post('/store', [CongVanController::class, 'store'])->name('cong-van.store');
});

Route::prefix('gioi-han-toc-do')->middleware('auth')->group(function () {
    Route::get('/', [GioiHanTocDoController::class, 'index'])->name('gioi-han-toc-do.index');
    Route::post('/store', [GioiHanTocDoController::class, 'store'])->name('gioi-han-toc-do.store');
    Route::get('/delete/{id}', [GioiHanTocDoController::class, 'delete'])->name('gioi-han-toc-do.delete');
});

Route::prefix('cap-phep')->middleware('auth')->group(function () {
    Route::get('/', [CapPhepController::class, 'index'])->name('cap-phep.index');
    Route::post('/store', [CapPhepController::class, 'store'])->name('cap-phep.store');
    Route::get('/delete/{id}', [CapPhepController::class, 'delete'])->name('cap-phep.delete');
});

Route::prefix('giam-sat')->middleware('auth')->group(function () {
    Route::get('/', [GiamSatController::class, 'index'])->name('giam-sat.index');
    Route::post('/store', [GiamSatController::class, 'store'])->name('giam-sat.store');
    Route::get('/delete/{id}', [GiamSatController::class, 'delete'])->name('giam-sat.delete');
});

Route::prefix('duong-cam')->middleware('auth')->group(function () {
    Route::get('/', [DuongCamController::class, 'index'])->name('duong-cam.index');
    Route::post('/store', [DuongCamController::class, 'store'])->name('duong-cam.store');
    Route::get('/delete/{id}', [DuongCamController::class, 'delete'])->name('duong-cam.delete');
});

Route::prefix('nguoi-dung')->middleware('auth') ->group(function () {
    Route::get('/', [NguoiDungController::class, 'index'])->name('nguoi-dung.index');
    Route::post('/store', [NguoiDungController::class, 'store'])->name('nguoi-dung.store');
});

Route::prefix('map')->middleware('auth')->group(function () {
    Route::get('/', [BanDoController::class, 'index'])->name('map.index');
    Route::post('/update-mota', [BanDoController::class, 'update_mota'])->name('map.update-mota');
});

Route::prefix('cau-hinh')->middleware('auth') ->group(function () {
    Route::get('/{loai}', [CauHinhController::class, 'index'])->name('cau-hinh.index');
    Route::post('/store', [CauHinhController::class, 'store'])->name('cau-hinh.store');
    Route::get('/delete/{id}', [CauHinhController::class, 'delete'])->name('cau-hinh.delete');
});


Route::prefix('diem-tai-nan')->middleware('auth')->group(function () {
    Route::get('/', [DiemTaiNanController::class, 'index'])->name('diem-tai-nan.index');
    Route::post('/store', [DiemTaiNanController::class, 'store'])->name('diem-tai-nan.store');
});

Route::prefix('den-giao-thong')->middleware('auth')->group(function () {
    Route::get('/', [DenGiaoThongController::class, 'index'])->name('den-giao-thong.index');
    Route::post('/store', [DenGiaoThongController::class, 'store'])->name('den-giao-thong.store');
    Route::get('/delete/{id}', [DenGiaoThongController::class, 'delete'])->name('den-giao-thong.delete');
});

Route::prefix('cau')->middleware('auth')->group(function () {
    Route::get('/', [CauController::class, 'index'])->name('cau.index');
    Route::post('/store', [CauController::class, 'store'])->name('cau.store');
    Route::get('/delete/{id}', [CauController::class, 'delete'])->name('cau.delete');
});

Route::get('/proxy-osrm', function (Request $request) {
    $start = $request->query('start');
    $end = $request->query('end');

    if (!$start || !$end) {
        return response()->json(['error' => 'Thiếu tham số tọa độ'], 400);
    }

    $url = "https://router.project-osrm.org/route/v1/driving/$start;$end?overview=full&geometries=geojson";

    $response = Http::get($url);

    return $response->json(); // Trả về JSON cho frontend
});

Route::prefix('location')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Location/Index');
    })->name('location.index');
});
