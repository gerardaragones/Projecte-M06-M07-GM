<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;

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

Route::apiResource('files', FileController::class);
Route::post('files/{file}', [FileController::class, 'update_workaround']);

Route::middleware('auth:sanctum')->post('/logout', [TokenController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', [TokenController::class, 'user']);
Route::middleware('guest')->post('/register',  [TokenController::class, 'register']);
Route::middleware('guest')->post('/login',  [TokenController::class, 'login']);

Route::middleware('auth:sanctum')->apiResource('posts', PostController::class);
Route::middleware('auth:sactum')->post('posts/{post}', [PostController::class, 'update_workaround']);
Route::middleware('auth:sanctum')->post('/posts/{posts}/likes', [PostController::class, 'like'])->name('post.like');

Route::middleware('auth:sanctum')->apiResource('posts/{post}/comments', CommentController::class);
Route::middleware('auth:sanctum')->post('posts/{posts}/comments/{comment}', [CommentController::class, 'update_workaround']);