<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/api/documentation');
});

// Welcome page (alternative route)
Route::get('/welcome', function () {
    return view('welcome');
});

// Swagger UI Routes
Route::get('/api/documentation', function () {
    return view('swagger.index');
});

Route::get('/docs', function () {
    return redirect('/api/documentation');
});

// Serve Swagger JSON file
Route::get('/storage/api-docs/api-docs.json', function () {
    $filePath = storage_path('api-docs/api-docs.json');
    
    if (!file_exists($filePath)) {
        abort(404, 'Swagger documentation not found');
    }
    
    return response()->file($filePath, [
        'Content-Type' => 'application/json',
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
    ]);
});