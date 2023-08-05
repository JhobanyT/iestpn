<?php

use App\Http\Controllers\ProgramaEstudioController;
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
    return view('index'); // Muestra la vista "index.php" cuando alguien accede a la raíz "/"
});

Route::get('/programaEstudios',ProgramaEstudioController::class);

// admin.php
/*Route::group(['middleware' => ['auth', 'auth.admin']], function () {
    // Rutas para el rol de administrador aquí...
    Route::get('/admin/dashboard', 'AdminController@dashboard')->name('admin.dashboard');
    // Otras rutas para administración...
});*/


