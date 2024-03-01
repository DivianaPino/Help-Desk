<?php

namespace App\Http\Controllers\Admin\Tickets;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Area;
use App\Models\Estado;
use App\Models\Respuesta;
use App\Models\MasInformacion;




class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets=Ticket::all();
        return view('myViews.Admin.tickets.index')->with('tickets', $tickets);
    }

    public function create()
    {
        return view('myViews.tickets.create');
    }


    public function area_tickets()
    {
        // Usuario autenticaco
        $usuario= Auth::user();

        if ($usuario->hasRole(['Jefe de área', 'Técnico de soporte'])) {

            // Area que pertenece el usuario
            $areasUsuario=$usuario->areas()->pluck('area_id');

            // Tickets que pertenecen al área del asuario
            $tickets=Ticket::whereIn('clasificacion_id', $areasUsuario)->get();


            // Pasar los tickets a la vista
            return view('myViews.Admin.tickets.ticketsArea')->with('tickets', $tickets) ;

        }
    }


    public function tickets_noasignados()
    {   
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
  
        $estadoNuevo = Estado::where('nombre', 'nuevo')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "nuevo"
        $ticketsNuevos = Ticket::whereIn('clasificacion_id', $areasUsuario)->where('estado_id', $estadoNuevo->id)->get();

        return view('myViews.Admin.tickets.noasignados')->with('tickets', $ticketsNuevos) ;
    }


    public function detalles_ticket($idTicket){

        $ticket=Ticket::find($idTicket);
        return view('myViews.Admin.tickets.detalles')->with(['ticket'=> $ticket]);
    }


    //* Método para que el técnico de soporte pueda asignarse un ticket
    public function asignar_ticket($idTicket){

        $ticket=Ticket::find($idTicket);
        // Asigna el usuario autenticado al ticket
        $ticket->asignado_a = Auth::user()->name;
         // cambiar estado a "abierto"
        $ticket->estado_id= 2;
        $ticket->save();

        // return redirect()->back()->with('status', 'Asignación exitosa. EL TICKET HA SIDO ABIERTO');
        return redirect()->route('form_Respuestaticket', ['idTicket' => $ticket->id])->with( 'status', 'Asignación exitosa. EL TICKET HA SIDO ABIERTO')->with('ticket', $ticket);

    }
    
    // TICKETS ASIGNADOS
    public function tickets_abiertos()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
 
        $estadoAbierto = Estado::where('nombre', 'abierto')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $ticketsAbiertos = Ticket::whereIn('clasificacion_id', $areasUsuario)->where('estado_id', $estadoAbierto->id)->get();

        return view('myViews.Admin.tickets.abiertos')->with('tickets', $ticketsAbiertos) ;
    }


    public function form_Respuestaticket($idTicket){

        $ticket=Ticket::find($idTicket);
        return view('myViews.Admin.tickets.form_respuesta')->with(['ticket'=> $ticket]);
    }
    

    public function guardar_respuestaTicket(Request $request, $idTicket){

        if($request->hasFile('imagen')){

            $file = $request->file('imagen'); // obtenemos el archivo
            $random_name = time(); // le colocamos a la imagen un nombre random y con el tiempo y fecha actual 
            $destinationPath = 'images/tickets/'; // path de destino donde estaran las imagenes subidas 
            $extension = $file->getClientOriginalExtension();
            $filename = $random_name.'-'.$file->getClientOriginalName(); //concatemos el nombre random creado anteriormente con el nombre original de la imagen (para evitar nombres repetidos)
            $uploadSuccess = $request->file('imagen')->move($destinationPath, $filename); //subimos y lo enviamos al path de Destin

            $respuesta= new Respuesta();
            $respuesta->ticket_id = $idTicket;
            $respuesta->mensaje=$request->mensaje;
            $respuesta->imagen=$filename;
            $respuesta->fecha=Carbon::now();
            $respuesta->save();

            
             // cambiar estado a "resuelto"
             $ticket=Ticket::find($idTicket);
             $ticket->estado_id= 4;
             $ticket->save();

        }else{

            $respuesta= new Respuesta();
            $respuesta->ticket_id = $idTicket;
            $respuesta->mensaje=$request->mensaje;
            $respuesta->fecha=Carbon::now();
            $respuesta->save();

             // cambiar estado a "resuelto"
             $ticket=Ticket::find($idTicket);
             $ticket->estado_id= 4;
             $ticket->save();

        }

        return back()->with('status', 'Ticket respondido exitosamente :)');
    }


    public function masInfo($idTicket){

        $ticket=Ticket::find($idTicket);
        $fecha_actual=Carbon::now()->format('d-m-Y');
        return view('myViews.Admin.tickets.masInfo')->with(['ticket'=> $ticket, 'fecha_actual'=> $fecha_actual]);;
    }

    
    public function guardar_masInfo(Request $request, $idTicket)
    {
       
        $request->validate([
                'mensaje' =>'required',
                'imagen' => 'image',
            ],
            [
                'mensaje.required' => 'El campo mensaje es requerido',
                'imagen.image' => 'El archivo debe ser una imagen',
            ]
        );

        
        if($request->hasFile('imagen')){

            $file = $request->file('imagen'); // obtenemos el archivo
            $random_name = time(); // le colocamos a la imagen un nombre random y con el tiempo y fecha actual 
            $destinationPath = 'images/tickets/'; // path de destino donde estaran las imagenes subidas 
            $extension = $file->getClientOriginalExtension();
            $filename = $random_name.'-'.$file->getClientOriginalName(); //concatemos el nombre random creado anteriormente con el nombre original de la imagen (para evitar nombres repetidos)
            $uploadSuccess = $request->file('imagen')->move($destinationPath, $filename); //subimos y lo enviamos al path de Destin

            $masInfo=new MasInformacion();
            $masInfo->ticket_id=$idTicket;   
            $masInfo->mensaje=$request->mensaje;
            $masInfo->imagen=$filename;
            $masInfo->fecha=Carbon::now();
            $masInfo->save();

            // cambiar estado a "en espera"
            $ticket=Ticket::find($idTicket);
            $ticket->estado_id= 3;
            $ticket->save();

        }else{

            $masInfo=new MasInformacion();
            $masInfo->ticket_id=$idTicket;   
            $masInfo->mensaje=$request->mensaje;
            $masInfo->fecha=Carbon::now();
            $masInfo->save();

             // cambiar estado a "en espera"
            $ticket=Ticket::find($idTicket);
            $ticket->estado_id= 3;
            $ticket->save();
        }

        return back()->with('status', 'Mensaje enviado exitosamente :)');
    
    }

    public function tickets_enEspera()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
        
        $estado_enEspera = Estado::where('nombre', 'En espera')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $tickets_enEspera = Ticket::whereIn('clasificacion_id', $areasUsuario)->where('estado_id', $estado_enEspera->id)->get();

        return view('myViews.Admin.tickets.enEspera')->with('tickets', $tickets_enEspera) ;
             
    }


    public function tickets_vencidos()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');

        $fecha_actual=Carbon::now();

        $ticketsVencidos = Ticket::whereIn('clasificacion_id', $areasUsuario)->where('fecha_caducidad', '<', $fecha_actual)->get();

        return view('myViews.Admin.tickets.vencidos')->with('tickets', $ticketsVencidos) ;
    }
    
    public function tickets_resueltos()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
        
        $estadoResuelto = Estado::where('nombre', 'Resuelto')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $ticketsResueltos = Ticket::whereIn('clasificacion_id', $areasUsuario)->where('estado_id', $estadoResuelto->id)->get();

        return view('myViews.Admin.tickets.resueltos')->with('tickets', $ticketsResueltos) ;
    }


    //Mostrar los usuarios de area con los tickets asignados
    public function tecnicos_tktAsignados(Request $request)
    {
         // usuario autenticado
        $usuario= Auth::user();

        // Id de las areas del usuario autenticado
        $areasUsuarioAuth=$usuario->areas()->pluck('area_id');

        // Usuarios que tiene el rol "soporte tecnico" y que pertenecen a las areas del usuario auth
        $usuarios = User::role('Técnico de soporte')
            ->whereHas('areas', function ($query) use ($areasUsuarioAuth) {
                $query->whereIn('area_id', $areasUsuarioAuth);
            })
            ->get();

        // Cantidad de veces que los usuarios tecnicos estan asignados a un ticket
        foreach ($usuarios as $usuario) {

            $usuario->ticket_count_a = Ticket::where('asignado_a', $usuario->name)->where('estado_id', 2 )->count();
            $usuario->ticket_count_esp = Ticket::where('asignado_a', $usuario->name)->where('estado_id', 3 )->count();
        }

        return view('myViews.Admin.tickets.asignarTecnico')->with(['usuarios'=> $usuarios]);
    }


    public function asignar_ticket_a_tecnico(Request $request, $usuarioId){

        // Obtener el usuario por el id pasado por parametro
        $usuario=User::find($usuarioId); 

        // Cantidad de tickets asignados que tiene el usuario seleccionado
        $Usuarioticket_Count = Ticket::where('asignado_a', $usuario->name)->count(); 
    
        // ASIGNAR TICKET A USUARIO SELECCIONADO
 
        $previousUrl = url()->previous();   // Url anteriormente visitada
        $ticketId = basename($previousUrl); // Obtenemos el id del ticket de la url anteriormente visitada
       
        $ticket=Ticket::find($ticketId);  // Obtenemos el ticket por el Id anteriormente obtenido

        $ticket->asignado_a =$usuario->name ;  // asignamos el nombre del usuario tecnico seleccionado
        $ticket->estado_id= 2;  // cambiar estado a "abierto"
        $ticket->save();  // Guardamos

        return redirect()->back()->with('status', 'Ticket asignado exitosamente');
           
    }

    public function tkt_abierto_tecnico($usuarioId){

        $usuario= User::find($usuarioId);
   
        if ($usuario->hasRole(['Técnico de soporte', 'Jefe de área'])) {

            $estadoAbierto = Estado::where('nombre', 'Abierto')->first();

            $usuario_ticketsAbiertos = Ticket::where('asignado_a', $usuario->name)->where('estado_id', $estadoAbierto->id)->get();

            return view('myViews.Admin.tickets.tkts_abiertos_tecnico')->with(['usuario'=> $usuario, 'tickets'=> $usuario_ticketsAbiertos]);

            
        }
    }

    public function tkt_enEspera_tecnico($usuarioId){

        $usuario= User::find($usuarioId);
   
        if ($usuario->hasRole(['Técnico de soporte', 'Jefe de área'])) {

            $estadoEspera = Estado::where('nombre', 'En espera')->first();

            $usuario_ticketsEspera = Ticket::where('asignado_a', $usuario->name)->where('estado_id', $estadoEspera->id)->get();

            return view('myViews.Admin.tickets.tkts_enEspera_tecnico')->with(['usuario'=> $usuario, 'tickets'=> $usuario_ticketsEspera]);
 
        } 
    }

    // Ver tickets desde la vista de los ticket asignados a los tecnicos(abiertos, en espera) 
    public function verTicket($idTicket){
        
       $ticket=Ticket::find($idTicket);
        return view('myViews.Admin.tickets.verTicket')->with(['ticket'=> $ticket]);
    }






    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
