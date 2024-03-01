@extends('adminlte::page')

@section('title', 'Mis tickets')

@section('content_header')
    <h1>Mis tickets</h1>
@stop

@section('content')
<div>
     <div  class="card">
        <div  class="card-body" >
            <table id="tabla_usuarios" class="table table-responsive table-striped table-bordered shadow-lg mt-4" style="width:100%; font-size:12px;" >
               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>ID</th>
                      <th>Usuario</th>
                      <th>Clasificación</th>
                      <th>Estado</th>
                      <th>Prioridad</th>
                      <th>Asunto</th>
                      <th>Mensaje</th>
                      <th>Agente</th>
                      <th>Creado</th>
                      <th>Respondido</th>
                      <th>Caducidad</th>
                      <th>Acciones</th>
                   </tr>
               </thead>

               <tbody>
                 
               @foreach ($tickets as $ticket )
   
                          <tr>
                             <td>TK-{{$ticket->id}}</td>
                             <td>{{$ticket->user->name}}</td>
                             <td>{{$ticket->clasificacion->nombre}}</td>
                             <td>{{$ticket->estado->nombre}}</td>
                             <td>{{$ticket->prioridad->nombre}}</td>
                             <td>{{$ticket->asunto}}</td>
                             <td>{{$ticket->mensaje}}</td>
                             <td>{{$ticket->asignado_a}}</td>
                             <td>{{$ticket->fecha_inicio}}</td>
                             <td>{{$ticket->fecha_fin}}</td>
                             <td>{{$ticket->fecha_caducidad}}</td>
                             <td>Botones de opciones</td>

                         </tr>

    

                  @endforeach

               </tbody>
            </table>
        </div>
     </div>
</div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tabla_usuarios').DataTable({
      //Opciones de paginación
        "lengthMenu": [
            [10, 30, 50, -1],
            [10, 30, 50, "All"]
        ],
        "language":{
            "info": "_TOTAL_ registros", 
            "search":"Buscar",
            "paginate": {
                "next": "Siguiente",
                "previous":"Anterior",
            },
            "lengthMenu":'Mostrar <select>'+
                        '<option value="10">10</option>'+
                        '<option value="30">30</option>'+
                        '<option value="50">50</option>'+
                        '<option value="-1">Todos</option>'+
                        '</select> registros',
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "emptyTable": "No hay datos",
            "zeroRecords":"No hay coincidencias",
            "infoEmpty": "",
            "infoFiltered":"",
        }
    });
});
</script>
@stop