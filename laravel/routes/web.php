<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\MailController;

use App\Http\Controllers\FileController;

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
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function (Request $request) {
    $request->session()->flash('info', 'TEST flash messages');
    return view('dashboard');
 })->middleware(['auth','verified'])->name('dashboard');;

require __DIR__.'/auth.php';


// ...
Route::get('mail/test', [MailController::class, 'test']);
// or
// Route::get('mail/test', 'App\Http\Controllers\MailController@test');


Route::resource('files', FileController::class)
        ->middleware(['auth', 'role:1']);

#Route::resource('post', PostController::class)
#        ->middleware(['auth', 'role:1']);