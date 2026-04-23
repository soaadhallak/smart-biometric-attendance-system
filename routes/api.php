<?php

use App\Http\Controllers\Api\Admin\AdminCourseStatsController;
use App\Http\Controllers\Api\Admin\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Student\AuthController;
use App\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\Admin\CourseController;
use App\Http\Controllers\Api\Admin\LectureController;
use App\Http\Controllers\Api\Admin\TeacherController;
use App\Http\Controllers\Api\Student\AddEnrollmentsForStudentController;
use App\Http\Controllers\Api\Student\AttendanceController;
use App\Http\Controllers\Api\Student\DeviceController;
use App\Http\Controllers\Api\Admin\DataCleanupController;
use App\Http\Controllers\Api\Student\DeleteAllEnrollmentsAndLecturesController;
use App\Http\Controllers\Api\Student\GetAllCoursesForStudentController;
use App\Http\Controllers\Api\Student\GetAllStudentLecturesController;
use App\Http\Controllers\Api\Student\StatisticsAttendanceController;

Route::get('/hello', function(){
    return "Hello world";
});

Route::prefix('auth')->group(function(){
    Route::post('/',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
});

Route::prefix('profile')->middleware(['auth:sanctum','role:student'])->group(function(){
    Route::patch('/',[AuthController::class,'editProfile']);
    Route::get('/', [AuthController::class, 'getProfile']);
});



Route::apiResource("user", StudentController::class)->middleware(['auth:sanctum','role:admin|teacher']);
Route::apiResource("teacher", TeacherController::class)->middleware(['auth:sanctum','role:admin|teacher']);

Route::prefix('auth/user')->group(function(){
    Route::post('/',[AdminAuthController::class,'register']);
    Route::post('/login',[AdminAuthController::class,'login']);
    Route::post('/logout',[AdminAuthController::class,'logout'])->middleware('auth:sanctum');
});

Route::apiResource("course", CourseController::class)->middleware(['auth:sanctum', 'role:admin|teacher']);
Route::apiResource("lecture", LectureController::class)->middleware(['auth:sanctum', 'role:admin|teacher']);

Route::get('/student/lectures',GetAllStudentLecturesController::class)->middleware(['auth:sanctum','role:student']);
Route::apiResource('attendances', AttendanceController::class)->middleware(['auth:sanctum','role:student']);  
Route::get('/statistics', StatisticsAttendanceController::class)->middleware(['auth:sanctum','role:student']);

Route::middleware(['auth:sanctum', 'role:admin|teacher'])->group(function () {
    Route::get('/admin/statistics', AdminCourseStatsController::class);
});

Route::post('/enrollments', AddEnrollmentsForStudentController::class)->middleware(['auth:sanctum', 'role:student']);
Route::get('/courses', GetAllCoursesForStudentController::class)->middleware(['auth:sanctum', 'role:student']);
Route::delete('/cleanup', DeleteAllEnrollmentsAndLecturesController::class)->middleware(['auth:sanctum', 'role:admin']);