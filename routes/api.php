<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;

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

// Public routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Authentication routes
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    
    // Student routes
    Route::apiResource('students', StudentController::class);
    
    // Course routes
    Route::apiResource('courses', CourseController::class);
    
    // Additional routes can be added here
    Route::get('/dashboard/stats', function () {
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_students' => \App\Models\Student::count(),
                'total_teachers' => \App\Models\Teacher::count(),
                'total_courses' => \App\Models\Course::count(),
                'active_enrollments' => \App\Models\Enrollment::where('status', 'active')->count(),
            ]
        ]);
    });
});