<?php

namespace App\Http\Controllers\usuarioEst\TicketsUsuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Carbon\Carbon;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Area;
use App\Models\Clasificacion;
use App\Models\Prioridad;
use App\Models\Estado;

class TicketsUsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets=Ticket::where('user_id', auth()->user()->id)->get();
        return view('myViews.usuarioEst.index')->with('tickets', $tickets);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usuarios=auth()->user();
        $clasificacions=Clasificacion::all();
        $prioridads=Prioridad::all();
        $fecha_actual=Carbon::now()->format('d-m-Y');
        $estadoFirst= Estado::first();
        $tecnicos= User::role('Técnico de soporte')->get();
        return view('myViews.usuarioEst.create')->with('usuarios', $usuarios)
                                                ->with('clasificacions', $clasificacions)
                                                ->with('prioridads', $prioridads)
                                                ->with('fecha_actual', $fecha_actual)
                                                ->with('estadoFirst', $estadoFirst)
                                                ->with('tecnicos', $tecnicos);
    }                        

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        

        $request->validate([
                'clasificacion_id' =>'required',
                'prioridad_id' =>'required',
                'asunto' =>'required',
                'mensaje' =>'required',
            ],
            [
                'clasificacion_id.required' => 'El campo clasificación es requerido',
                'prioridad_id.required' => 'El campo prioridad es requerido',
                'asunto.required' => 'El campo asunto es requerido',
                'mensaje.required' => 'El campo mensaje es requerido',
            ]
        );

      
        $prioridad = Prioridad::find($request->prioridad_id); // Obtén la prioridad por ID
        $tiempoResolucion = $prioridad->tiempo_resolucion;


        $ticket=new Ticket();
        $ticket->user_id=auth()->id();    
        $ticket->clasificacion_id=$request->clasificacion_id;
        $ticket->prioridad_id=$request->prioridad_id;
        $ticket->asunto=$request->asunto;
        $ticket->mensaje=$request->mensaje;
        $ticket->fecha_inicio=Carbon::now();
        $ticket->estado_id=Estado::first()->id;
        $ticket->fecha_caducidad=Carbon::now()->addDays($tiempoResolucion);
        $ticket->save();

        return back()->with('status', 'Ticket enviado exitosamente :)');
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


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
