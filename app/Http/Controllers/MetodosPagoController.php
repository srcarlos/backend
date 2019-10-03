<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\MetodosPago;
use Barryvdh\DomPDF\Facade as PDF;

class MetodosPagoController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'nombre' => 'required|unique:metodos_pagos,nombre',
            'descripcion' => 'required',
            'estado' => 'required|boolean'
        ]);

        MetodosPago::create($request->all());

        return response()->success("Metodo de pago creado exitosamente");
    }

    public function show($id)
    {
        $metodo = MetodosPago::find($id);

        if (!$metodo){
            return response()->json('El metodo de pago no existe',404);
        }

        return response()->success(compact('metodo'));
    }

    public function update(Request $request,$id)
    {
        $metodo = MetodosPago::find($id);

        if (!$metodo){
            return response()->json('El metodo de pago no existe',404);
        }

        $this->validate($request,[
            'nombre' => 'required|unique:metodos_pagos,nombre,'.$metodo->id,
            'descripcion' => 'required',
            'estado' => 'required|boolean'
        ]);

        $metodo->nombre = $request->nombre;
        $metodo->descripcion = $request->descripcion;
        $metodo->estado = $request->estado;
        $metodo->save();

        return response()->success('El metodo de pago ha sido modificado exitosamente');
    }

    public function destroy($id)
    {
        $metodo = MetodosPago::find($id);

        if (!$metodo){
            return response()->json('El metodo de pago no existe',404);
        }

        $metodo->delete();

        return response()->success('El metodo de pago ha sido eliminado exitosamente');
    }

    public function index()
    {
        $metodo = MetodosPago::all();

        return response()->success(compact('metodo'));
    }

    public function search(Request $request)
    {
        $metodo = MetodosPago::where('nombre','like',$request->data.'%')
            ->orWhere('descripcion','like',$request->data.'%')
            ->orWhere('estado','like',$request->data.'%')
            ->get();

        return response()->success(compact('metodo'));
    }

    public function csv()
    {
        $metodo = MetodosPago::all();

        $csv = new \Laracsv\Export();

        $csv->build($metodo, ['nombre','descripcion','estado'])->download('metodos_de_pago.csv');
    }

    public function pdf()
    {
        $metodos = MetodosPago::all();

        $pdf = PDF::loadView('PDF.metodos_pdf',compact('metodos'));

        return $pdf->download('metodos_de_pago.pdf');
    }
}
