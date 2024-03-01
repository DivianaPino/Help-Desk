<?php

namespace App\Http\Controllers\Admin\Areas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Clasificacion;


class AreasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas=Area::all();
        return view('myViews.Admin.areas.index')->with('areas', $areas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('myViews.Admin.areas.create');
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
                'nombre' =>'required',
            ],
            [
                'nombre.required' => 'El campo nombre del área o departamento es requerido',
            ]
        );

        // Llenar los datos de area tambien en la tabla clasificacion
        $area=new Area();
        $clasificacion=new Clasificacion();
        $area->nombre=$request->nombre;
        $clasificacion->nombre =$request->nombre;
        $area->save();
        $clasificacion->save();

        return redirect('/areas')->with('status', 'Área creada exitosamente :)');
    
    }

    public function area_tecnicos($areaid)
    { 
        $area = Area::find($areaid);
        $usuarios = $area->users; // Obtiene todos los usuarios de un área específica
        return view('myViews.Admin.areas.tecnicos')->with(['usuarios'=> $usuarios, 'area'=>$area]);
    }

    public function edit($id)
    {
        $area=Area::find($id);
        return view('myViews.Admin.areas.edit')->with('area', $area);
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
        $request->validate([
            'nombre' =>'required',
        ],
        [
            'nombre.required' => 'El campo nombre del área o departamento es requerido',
        ]
    );

        $area=Area::find($id);
        $clasificacion=Clasificacion::find($id);
        $area->nombre=$request->nombre;
        $clasificacion->nombre =$request->nombre;
        $area->save();
        $clasificacion->save();

        return redirect('/areas')->with('status', 'Área editada exitosamente :)');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $area= Area::find($id);
        $clasificacion= Clasificacion::find($id);
        $area->delete(); 
        $clasificacion->delete();
        return redirect('/areas');
    }
}
