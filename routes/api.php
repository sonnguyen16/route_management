<?php

use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\PlanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Location API routes
Route::middleware(['web', 'auth'])->group(function () {
    // Location tracking routes
    Route::prefix('location')->group(function () {
        Route::post('/', [LocationController::class, 'store']);
        Route::get('/history', [LocationController::class, 'getHistory']);
        Route::get('/active-captains', [LocationController::class, 'getActiveCaptains']);
        Route::get('/captain/{captainId}/history', [LocationController::class, 'getCaptainHistory']);
    });

    // Plans API routes
    Route::prefix('plans')->group(function () {
        Route::get('/', [PlanController::class, 'index']);
        Route::post('/', [PlanController::class, 'store']);
        Route::get('/today', [PlanController::class, 'today']);
        Route::get('/{id}', [PlanController::class, 'show']);
        Route::put('/{id}', [PlanController::class, 'update']);
        Route::delete('/{id}', [PlanController::class, 'destroy']);
    });
});
