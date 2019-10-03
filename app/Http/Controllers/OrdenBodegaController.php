<?php

namespace App\Http\Controllers;

use App\OrdenBodega;
use Illuminate\Http\Request;

use App\Http\Requests;

class OrdenBodegaController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'descripcion' => 'required',
            'cocina_id' => 'required',
            'bodega_id' => 'required',
            'fecha' => 'required',
            'detalles.*.insumo_id' => 'required',
            'detalles.*.unidad' => 'required',
            'detalles.*.proveedor_id' => 'required',
            'detalles.*.precio_unitario' => 'required',
            'detalles.*.cantidad' => 'required',
            'detalles.*.total' => 'required',
        ]);

        $orden = OrdenBodega::create($request->only(['descripcion','cocina_id','bodega_id','fecha']));

        $orden->detalles()->createMany($request->detalles);

        return response()->success('Orden creada exitosamente');
    }

    public function index($id)
    {
        $orden = OrdenBodega::where('bodega_id',$id)->with([
            "bodega" => function($q){ $q->select('id','nombre'); },
            "cocina" => function($q){ $q->select('id','nombre'); }
        ])->select('id','descripcion','fecha','bodega_id','cocina_id','estatus')->get();

        return response()->success(compact('orden'));
    }

    public function show($id)
    {
        $orden = OrdenBodega::where('id',$id)->with([
            "bodega" => function($q){ $q->select('id','nombre'); },
            "cocina" => function($q){ $q->select('id','nombre'); },
            "detalles.proveedor" => function($q){ $q->select('id','nombre','apellido'); },
            "detalles.insumo" => function($q){ $q->select('id','nombre'); },
            "detalles.unidad_medida" => function($q){ $q->select('id','nombre','abreviacion'); },
        ])->select('id','descripcion','fecha','bodega_id','cocina_id','estatus')->first();

        if (!$orden){
            return response()->error('La orden no existe');
        }

        return response()->success(compact('orden'));
    }

    public function update(Request $request,$id)
    {
        $orden = OrdenBodega::find($id);

        if (!$orden){
            return response()->error('La orden no existe');
        }

        $this->validate($request,[
            'descripcion' => 'required',
            'cocina_id' => 'required',
            'bodega_id' => 'required',
            'fecha' => 'required',
            'detalles.*.insumo_id' => 'required',
            'detalles.*.unidad' => 'required',
            'detalles.*.proveedor_id' => 'required',
            'detalles.*.precio_unitario' => 'required',
            'detalles.*.cantidad' => 'required',
            'detalles.*.total' => 'required',
        ]);

        $orden->descripcion = $request->descripcion;
        $orden->fecha = $request->fecha;
        $orden->save();

        $orden->detalles()->delete();

        $orden->detalles()->createMany($request->detalles);

        return response()->success('La orden ha sido modificada exitosamente');
    }

    public function destroy($id)
    {
        $orden = OrdenBodega::find($id);

        if (!$orden){
            return response()->error('La orden no existe');
        }

        $orden->delete();

        return response()->success('La orden ha sido eliminada exitosamente');
    }
}
