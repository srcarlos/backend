<?php

namespace App\Http\Controllers;

use App\Impuesto;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;

use App\Http\Requests;

class ImpuestosController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'nombre' => 'required',
            'descripcion' => 'required',
            'porcentaje' => 'required',
        ]);

        Impuesto::create($request->all());

        return response()->success("Impuesto creado exitosamente");
    }

    public function show($id)
    {
        $impuesto = Impuesto::find($id);

        if (!$impuesto){
            return response()->json('El impuesto no existe',404);
        }

        return response()->success(compact('impuesto'));
    }

    public function update(Request $request,$id)
    {
        $impuesto = Impuesto::find($id);

        if (!$impuesto){
            return response()->json('El impuesto no existe',404);
        }

        $this->validate($request,[
            'nombre' => 'required',
            'descripcion' => 'required',
            'porcentaje' => 'required',
        ]);

        $impuesto->nombre = $request->nombre;
        $impuesto->descripcion = $request->descripcion;
        $impuesto->porcentaje = $request->porcentaje;
        $impuesto->save();

        return response()->success('El impuesto ha sido modificado exitosamente');
    }

    public function destroy($id)
    {
        $impuesto = Impuesto::find($id);

        if (!$impuesto){
            return response()->json('El impuesto no existe',404);
        }

        $impuesto->delete();

        return response()->success('El impuesto ha sido eliminado exitosamente');
    }

    public function index()
    {
        $impuestos = Impuesto::all();

        return response()->success(compact('impuestos'));
    }

    public function search(Request $request)
    {
        $impuestos = Impuesto::where('nombre','like',$request->data.'%')
            ->orWhere('descripcion','like',$request->data.'%')
            ->orWhere('porcentaje','like',$request->data.'%')
            ->get();

        return response()->success(compact('impuestos'));
    }

    public function csv()
    {
        $impuestos = Impuesto::all();

        $csv = new \Laracsv\Export();

        $csv->build($impuestos, ['nombre','descripcion','porcentaje'])->download('impuestos.csv');
    }

    public function pdf()
    {
        $impuestos = Impuesto::all();

        $pdf = PDF::loadView('PDF.impuestos_pdf',compact('impuestos'));

        return $pdf->download('impuestos.pdf');
    }
}
