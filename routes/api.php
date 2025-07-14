<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TeacherDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClassGroupController;
use App\Http\Controllers\Admin\ProfileFieldController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('role:student')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::post('/profile', [ProfileController::class, 'store']);
    });

    Route::middleware('role:teacher')->group(function () {
        Route::get('/teacher', [TeacherDashboardController::class, 'index']);
        Route::get('/teacher/filter', [TeacherDashboardController::class, 'filterStudents']);
    });
});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {

    Route::apiResource('users', UserController::class)->only(['index', 'update', 'destroy']);

    Route::apiResource('class-groups', ClassGroupController::class)->only(['index', 'update']);
    Route::apiResource('profile-fields', ProfileFieldController::class)->only(['index', 'store', 'destroy']);
});

