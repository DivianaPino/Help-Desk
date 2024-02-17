@extends('adminlte::page')

@section('title', 'Prioridades')

@section('content_header')
    <h1 style="text-align:center; font-size: 40px; font-weight:bold; color:#566573">Todas las prioridades</h1>
@stop

@section('content')
<div>    
     <div  class="card">
        <div  class="card-body col-md-8" style="margin:0 auto;" >
            <table id="tabla_areas" class="table  table-striped table-bordered shadow-lg mt-4" style="font-size:15px; text-align: center;" >
            <a href="prioridades/create" class="btn btn-primary mb-3" style="width:100px; font-size:20px;">Crear</a>
               @if(session('status'))
                <p class="alert alert-success">{{ Session('status') }}</p>
               @endif

               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Días de resolución</th>
                      <th>Acciones</th>
                   </tr>
               </thead>

               <tbody >
                    @foreach ($prioridades as $prioridad )
                        <tr>
                            <td>{{$prioridad->id}}</td>
                            <td>{{$prioridad->nombre}}</td>
                            <td>{{$prioridad->tiempo_resolucion}}</td>
                            <td style="text-align: center;">
                                <form action="{{route('prioridades.destroy',$prioridad->id)}}" method="POST">
                                    <a href="/prioridades/{{$prioridad->id}}/edit" class="btn btn-warning">Editar</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger ">Borrar</button>
                                </form> 
                            </td>
                        </tr>
                    @endforeach
               </tbody>
            </table>
        </div>
     </div>
</div>

@stop

@section('css')
      <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tabla_areas').DataTable({
      //Opciones de paginación
        "lengthMenu": [
            [5, 10, 50, -1],
            [5, 10, 50, "All"]
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