<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ticket;


class DashboardController extends Controller
{
    public function index(){


        if(Auth::check()){
            if(Auth::user()->hasRole('Administrador')){
                $tickets=Ticket::all();
                return view('myViews.tickets.index')->with('tickets', $tickets);
                // dd('administrador');
            }else{
                 $tickets=Ticket::where('user_id', auth()->user()->id)->get();
                 return view('myViews.consulta_tickets.index')->with('tickets', $tickets);
                // dd('otro user');
    
            }
        }
    }
}

