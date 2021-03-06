<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfilePhotoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group( function () {

    Route::get('/dashboard', function (Request $request) {
        $user = auth()->user();
        return view('dashboard', ['user' => $user]);
    })->name('dashboard');

    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::put('/users/{user}/images', [ProfilePhotoController::class, 'update']);
    
});

require __DIR__.'/auth.php';
