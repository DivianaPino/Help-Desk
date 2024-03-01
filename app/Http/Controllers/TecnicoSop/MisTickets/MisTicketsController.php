<?php

namespace App\Http\Controllers\TecnicoSop\MisTickets;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ticket;

class MisTicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function misTickets_agenteTecnico()
    {
        $usuario= Auth::user();
        $tickets = $usuario->tickets;

        return view('myViews.tecnicoSop.misTickets.index')->with('tickets', $tickets);
    }

 
}
