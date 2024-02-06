<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\TokenController;

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

Route::apiResource('files', FileController::class);
Route::post('files/{file}', [FileController::class, 'update_workaround']);

// Ruta para obtener información de usuario
Route::middleware('auth:sanctum')->get('/user', [TokenController::class, 'user']);

// Ruta para registrar un nuevo usuario
Route::middleware('guest')->post('/register', [TokenController::class, 'register']);

// Ruta para iniciar sesión
Route::middleware('guest')->post('/login', [TokenController::class, 'login']);

// Ruta para cerrar sesión
Route::middleware('auth:sanctum')->post('/logout', [TokenController::class, 'logout']);