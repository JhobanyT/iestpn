<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProgramaEstudioController;
use Illuminate\Support\Facades\Route;


Route::get('/login', function () {
    return view('auth.login');
});

Route::resource('/programaEstudios',ProgramaEstudioController::class);
