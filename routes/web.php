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
use App\Http\Controllers\Admin\Analisis\AnalisisController;

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


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'] )->name('dashboard');

    Route::resource('/usuarios', UsuariosController::class)->names('usuarios');
    Route::resource('/tickets', TicketsController::class)->names('tickets');  
    Route::resource('/usuario/tickets', TicketsUsuarioController::class)->names('usuarios_tickets');
    Route::resource('/areas', AreasController::class)->names('areas');
    Route::resource('/prioridades', PrioridadesController::class)->names('prioridades');


    Route::get('/asignar_area/{id}', [UsuariosController::class, 'asignar_area'] )->name('asignar_area');
    Route::put('/actualizar_area/{id}', [UsuariosController::class, 'actualizar_area'])->name('actualizar_area');

    Route::get('/area/{areaId}/tecnicos', [AreasController::class, 'area_tecnicos'] )->name('area_tecnicos');

    Route::get('/area_usuario/tickets', [TicketsController::class, 'area_tickets'] )->name('areaUsuario_tickets');
    Route::get('/misTickets', [MisTicketsController::class, 'misTickets_agenteTecnico'] )->name('misTickets');

    


    Route::get('/noasignados', [TicketsController::class, 'tickets_noasignados'] )->name('tickets_noasignados');
    Route::get('/detalles/{idTicket}', [TicketsController::class, 'detalles_ticket'] )->name('detalles_ticket');
    Route::put('/asignarTicket/{idTicket}', [TicketsController::class, 'asignar_ticket'] )->name('asignar_ticket');
    Route::get('/abiertos', [TicketsController::class, 'tickets_abiertos'] )->name('tickets_abiertos');
    Route::get('/form/respuesta/{idTicket}', [TicketsController::class, 'form_Respuestaticket'] )->name('form_Respuestaticket');
    Route::post('/respuesta/ticket/{idTicket}', [TicketsController::class, 'guardar_respuestaTicket'] )->name('guardar_respuestaTicket');
    Route::get('/masInformación/{idTicket}', [TicketsController::class, 'masInfo'] )->name('masInfo');
    Route::post('/masInformacion/ticket/{idTicket}', [TicketsController::class, 'guardar_masInfo'] )->name('guardar_masInfo');
    Route::get('/enEspera', [TicketsController::class, 'tickets_enEspera'] )->name('tickets_enEspera');
    Route::get('/vencidos', [TicketsController::class, 'tickets_vencidos'] )->name('tickets_vencidos');
    Route::get('/resueltos', [TicketsController::class, 'tickets_resueltos'] )->name('tickets_resueltos');
    Route::get('/tecnicos/tickets/asignados/{idTicket}', [TicketsController::class, 'tecnicos_tktAsignados'] )->name('tecnicos_tkt_asignados');
    Route::post('/asignar/tecnico/{user}/ticket', [TicketsController::class, 'asignar_ticket_a_tecnico'] )->name('asignar_tecnico_ticket');

    Route::get('/tickets/abiertos/tecnico/{user}', [TicketsController::class, 'tkt_abierto_tecnico'] )->name('kt_abierto_tecnico');
    Route::get('/tickets/enEspera/tecnico/{user}', [TicketsController::class, 'tkt_enEspera_tecnico'] )->name('tkt_enEspera_tecnico');

    Route::get('/ver/ticket/{idTicket}', [TicketsController::class, 'verTicket'] )->name('verTicket');
    
    

    Route::get('/analisis', [AnalisisController::class, 'index'] )->name('index');

    

    

});
