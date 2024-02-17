<?php

use Illuminate\Support\Facades\Route;

//General
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Dashboard\DashboardController;

//Admin
use App\Http\Controllers\Admin\Tickets\TicketsController;
use App\Http\Controllers\Admin\Usuarios\UsuariosController;
use App\Http\Controllers\Admin\Areas\AreasController;
use App\Http\Controllers\Admin\Prioridades\PrioridadesController;

//Técnico de soporte
use App\Http\Controllers\TecnicoSop\MisTickets\MisTicketsController;

//Usuario Estándar
use App\Http\Controllers\usuarioEst\TicketsUsuario\TicketsUsuarioController;

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

Route::get('/', [HomeController::class, 'index'] );
Route::get('/dashboard', [DashboardController::class, 'index'] )->name('dashboard');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    

    Route::resource('/usuarios', UsuariosController::class);
    Route::resource('/tickets', TicketsController::class);  
    Route::resource('/tickets/usuario', TicketsUsuarioController::class);
    Route::resource('/areas', AreasController::class);
    Route::resource('/prioridades', PrioridadesController::class);


    Route::get('/asignar_area/{id}', [UsuariosController::class, 'asignar_area'] )->name('asignar_area');
    Route::put('/actualizar_area/{id}', [UsuariosController::class, 'actualizar_area'] );

    Route::get('/area/{areaId}/usuarios', [AreasController::class, 'area_usuarios'] )->name('area_usuarios');

});
