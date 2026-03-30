<?php

use App\Http\Controllers\Api\Admin\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Student\AuthController;
use App\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\Admin\CourseController;
use App\Http\Controllers\Api\Admin\LectureController;
use App\Http\Controllers\Api\Admin\TeacherController;
use App\Http\Controllers\Api\Student\GetAllStudentLecturesController;

Route::get('/hello', function(){
    return "Hello world";
});

Route::prefix('auth')->group(function(){
    Route::post('/',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
});


Route::apiResource("user", StudentController::class)->middleware(['auth:sanctum','role:admin']);
Route::apiResource("teacher", TeacherController::class)->middleware(['auth:sanctum','role:admin']);

Route::prefix('auth/user')->group(function(){
    Route::post('/',[AdminAuthController::class,'register']);
    Route::post('/login',[AdminAuthController::class,'login']);
    Route::post('/logout',[AdminAuthController::class,'logout'])->middleware('auth:sanctum');
});

Route::apiResource("course", CourseController::class)->middleware(['auth:sanctum', 'role:admin']);
Route::apiResource("lecture", LectureController::class)->middleware(['auth:sanctum', 'role:admin']);

Route::get('/student/lectures',GetAllStudentLecturesController::class)->middleware(['auth:sanctum','role:student']);
