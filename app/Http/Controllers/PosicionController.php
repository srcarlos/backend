<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Posicion;

class PosicionController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'nombre' => 'required|unique:posiciones,nombre',
            'codigo' => 'required|unique:posiciones,codigo',
            'seccion_id' => 'required|numeric',
        ]);

        Posicion::create($request->all());

        return response()->success("Posición creada exitosamente");
    }

    public function show($id)
    {
        $posicion = Posicion::find($id);

        if (!$posicion){
            return response()->json('La posición no existe',404);
        }

        return response()->success(compact('posicion'));
    }

    public function update(Request $request,$id)
    {
        $posicion = Posicion::find($id);

        if (!$posicion){
            return response()->json('La posición no existe',404);
        }

        $this->validate($request,[
            'nombre' => 'required|unique:posiciones,nombre,'.$posicion->id,
            'codigo' => 'required|unique:posiciones,codigo,'.$posicion->id,
            'seccion_id' => 'required|numeric',
        ]);

        $posicion->nombre = $request->nombre;
        $posicion->codigo = $request->codigo;
        $posicion->seccion_id = $request->seccion_id;
        $posicion->save();

        return response()->success('La posición ha sido modificada exitosamente');
    }

    public function destroy($id)
    {
        $posicion = Posicion::find($id);

        if (!$posicion){
            return response()->json('La posición no existe',404);
        }

        $posicion->delete();

        return response()->success('La posición ha sido eliminada exitosamente');
    }

    public function index()
    {
        $posicion = Posicion::all();

        return response()->success(compact('posicion'));
    }}
