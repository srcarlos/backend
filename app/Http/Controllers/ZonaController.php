<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Zona;
use Barryvdh\DomPDF\Facade as PDF;

class ZonaController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'nombre' => 'required|unique:zonas,nombre',
            'estado' => 'boolean',
        ]);

        Zona::create($request->all());

        return response()->success("Zona creada exitosamente");
    }

    public function show($id)
    {
        $zona = Zona::find($id);

        if (!$zona){
            return response()->json('La zona no existe',404);
        }

        return response()->success(compact('zona'));
    }

    public function update(Request $request,$id)
    {
        $zona = Zona::find($id);

        if (!$zona){
            return response()->json('La zona no existe',404);
        }

        $this->validate($request,[
            'nombre' => 'required|unique:zonas,nombre,'.$zona->id,
            'estado' => 'boolean',
        ]);

        $zona->nombre = $request->nombre;
        $zona->estado = $request->estado;
        $zona->save();

        return response()->success('La zona ha sido modificada exitosamente');
    }

    public function destroy($id)
    {
        $zona = Zona::find($id);

        if (!$zona){
            return response()->json('La zona no existe',404);
        }

        $zona->delete();

        return response()->success('La zona ha sido eliminada exitosamente');
    }

    public function index()
    {
        $zona = Zona::all();

        return response()->success(compact('zona'));
    }

    public function search(Request $request)
    {
        $zona = Zona::where('nombre','like',$request->data.'%')->get();

        return response()->success(compact('zona'));
    }

    public function csv()
    {
        $zona = Zona::all();

        $csv = new \Laracsv\Export();

        $csv->build($zona, ['nombre' => 'Nombre','estado' => 'Estado'])->download('zonas.csv');
    }

    public function pdf()
    {
        $zonas = Zona::all();

        $pdf = PDF::loadView('PDF.zonas_pdf',compact('zonas'));

        return $pdf->download('zonas.pdf');
    }
}
