<?php

use App\Http\Controllers\ProgramaEstudioController;
use App\Http\Controllers\TrabajoAplicacionController;
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
    return view('auth.login');
});
Route::resource('/programaEstudios',ProgramaEstudioController::class);
Route::resource('/trabajoAplicacion',TrabajoAplicacionController::class);
// Route::get('/trabajoAplicacion/{taplicacion}', [App\Http\Controllers\TrabajoAplicacionController::class, 'show'])->name('trabajoAplicacion.show');
