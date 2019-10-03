<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Seccion;
use Barryvdh\DomPDF\Facade as PDF;

class SeccionController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'nombre' => 'required|unique:secciones,nombre',
            'codigo' => 'required|unique:secciones,codigo',
            'bodega_id' => 'required|numeric',
        ]);

        Seccion::create($request->all());

        return response()->success("Sección creada exitosamente");
    }

    public function show($id)
    {
        $seccion = Seccion::find($id);

        if (!$seccion){
            return response()->json('La sección no existe',404);
        }

        return response()->success(compact('seccion'));
    }

    public function update(Request $request,$id)
    {
        $seccion = Seccion::find($id);

        if (!$seccion){
            return response()->json('La sección no existe',404);
        }

        $this->validate($request,[
            'nombre' => 'required|unique:secciones,nombre,'.$seccion->id,
            'codigo' => 'required|unique:secciones,codigo,'.$seccion->id,
            'bodega_id' => 'required|numeric',
        ]);

        $seccion->nombre = $request->nombre;
        $seccion->codigo = $request->codigo;
        $seccion->bodega_id = $request->bodega_id;
        $seccion->save();

        return response()->success('La sección ha sido modificada exitosamente');
    }

    public function destroy($id)
    {
        $seccion = Seccion::find($id);

        if (!$seccion){
            return response()->json('La sección no existe',404);
        }

        $seccion->delete();

        return response()->success('La sección ha sido eliminada exitosamente');
    }

    public function index()
    {
        $seccion = Seccion::all();

        return response()->success(compact('seccion'));
    }

    public function posicionesBySeccion($id)
    {
        $seccion = Seccion::find($id);
        $posicion = $seccion->posiciones;

        return response()->success(compact('posicion'));
    }
}
