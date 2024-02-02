<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Rol\RolController;
use App\Http\Controllers\Usuario\UsuarioController;
use App\Http\Controllers\Tickets\TicketsController;
use App\Http\Controllers\MisTickets\MisTicketsController;
use App\Http\Controllers\ConsultarTicket\ConsultarTicketController;

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
Route::get('/dashboard', [DashboardController::class, 'index'] );

//  Route::get('/usuarios', [UsuarioController::class, 'index'] )->name('usuarios');
//  Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'] )->name('usuarios.edit');
//  Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'] )->name('usuarios.update');



Route::resources([
    '/usuarios' => UsuarioController::class,
    '/asignar_rol' => RolController::class,
    '/Tickets' => TicketsController::class,
    '/misTickets' => MisTicketsController::class,
    '/consultarTickets' => ConsultarTicketController::class,
]);



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/consultarTickets', [ConsultarTicketController::class, 'index'] )->name('consultarTickets.index');
});
