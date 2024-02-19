<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\PlaceController;
use App\Http\Controllers\Api\ReviewController;

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

Route::middleware('auth:sanctum')->get('/user', [TokenController::class, 'user']);
Route::middleware('guest')->post('/register', [TokenController::class, 'register']);
Route::middleware('guest')->post('/login', [TokenController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [TokenController::class, 'logout']);

Route::middleware('auth:sanctum')->apiResource('places', PlaceController::class);
Route::middleware('auth:sanctum')->post('places/{place}', [PlaceController::class, 'update_workaround']);
Route::middleware('auth:sanctum')->post('/places/{place}/favorites', [PlaceController::class, 'favorite'])->name('places.favorite');

Route::middleware('auth:sanctum')->apiResource('places/{place}/reviews', ReviewController::class);
Route::middleware('auth:sanctum')->post('places/{places}/reviews/{review}', [ReviewController::class, 'update_workaround']);