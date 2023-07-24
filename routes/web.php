<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProgramaEstudioController;



Route::get('/login', function () {
    return view('auth.login');
});

Route::resource('/programaEstudios',ProgramaEstudioController::class);

Route::get('/', function () {
    return view('home');
})->middleware('auth');

Route::get('/register', [RegisterController::class, 'create'])
        ->middleware('auth')
        ->name('register.index');

Route::post('/register', [RegisterController::class, 'store'])
        ->name('register.store');


Route::get('/login', [SessionsController::class, 'create'])
        ->middleware('guest')
        ->name('login.index');

Route::post('/login', [SessionsController::class, 'store'])
        ->name('login.store');

Route::get('/logout', [SessionsController::class, 'destroy'])
        ->middleware('auth')
        ->name('login.destroy');


/*Route::get('/Admin/programaEstudios', [AdminController::class, 'index'])
        ->middleware('auth.admin')
        ->name('admin.index');*/
