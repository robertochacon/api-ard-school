<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\SesionController;

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

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Authentication routes
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    
    // Student routes
    Route::apiResource('estudiantes', StudentController::class);
    
    // Teacher routes
    Route::apiResource('docentes', TeacherController::class);
    
    // Asignatura routes
    Route::apiResource('asignaturas', AsignaturaController::class);
    
    // Sesion routes
    Route::apiResource('sesiones', SesionController::class);
    Route::prefix('sesiones/{id}')->group(function () {
        Route::get('asignaturas', [SesionController::class, 'getAsignaturas']);
        Route::put('asignaturas', [SesionController::class, 'updateAsignaturas']);
        Route::get('estudiantes', [SesionController::class, 'getEstudiantes']);
        Route::put('estudiantes', [SesionController::class, 'updateEstudiantes']);
        Route::get('calificaciones', [SesionController::class, 'getCalificaciones']);
        Route::put('calificaciones', [SesionController::class, 'updateCalificaciones']);
        Route::get('asistencia', [SesionController::class, 'getAsistencia']);
        Route::post('asistencia', [SesionController::class, 'updateAsistencia']);
    });
    
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