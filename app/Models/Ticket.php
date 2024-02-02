<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public function user()
    {
      return $this->belongsTo('App\Models\User');
    } 

    public function clasificacion()
    {
      return $this->belongsTo('App\Models\Clasificacion');
    } 

    public function estado()
    {
      return $this->belongsTo('App\Models\Estado');
    } 

    public function prioridad()
    {
      return $this->belongsTo('App\Models\Prioridad');
    } 

    public function respuesta() 
    {
        return $this->hasOne('App\Models\Respuesta');
    }

    





}
