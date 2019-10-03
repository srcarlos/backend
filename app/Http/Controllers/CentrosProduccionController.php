<?php

namespace App\Http\Controllers;

use App\Cocina;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\CentroProduccion;
use Barryvdh\DomPDF\Facade as PDF;

class CentrosProduccionController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'nombre' => 'required',
            'descripcion' => 'required',
            'direccion' => 'required',
            'responsable' => 'required',
            'tlf_responsable' => 'required',
            'compania_id' => 'required',
        ]);

        CentroProduccion::create($request->all());

        return response()->success("Centro de produccion creado exitosamente");
    }

    public function show($id)
    {
        $centro = CentroProduccion::find($id);

        if (!$centro){
            return response()->json('El Centro de produccion no existe',404);
        }

        return response()->success(compact('centro'));
    }

    public function update(Request $request,$id)
    {
        $centro = CentroProduccion::find($id);

        if (!$centro){
            return response()->json('El Centro de produccion no existe',404);
        }

        $this->validate($request,[
            'nombre' => 'required',
            'descripcion' => 'required',
            'direccion' => 'required',
            'responsable' => 'required',
            'tlf_responsable' => 'required',
        ]);

        $centro->nombre = $request->nombre;
        $centro->direccion = $request->direccion;
        $centro->descripcion = $request->descripcion;
        $centro->responsable = $request->responsable;
        $centro->tlf_responsable = $request->tlf_responsable;
        $centro->save();

        return response()->success('El Centro de produccion ha sido modificado exitosamente');
    }

    public function destroy($id)
    {
        $centro = CentroProduccion::find($id);

        if (!$centro){
            return response()->json('El Centro de produccion no existe',404);
        }

        $centro->delete();

        return response()->success('El Centro de produccion ha sido eliminado exitosamente');
    }

    public function index()
    {
        $centro = CentroProduccion::all();

        return response()->success(compact('centro'));
    }

    public function cocinasByCentro($id)
    {
        $cocina = Cocina::where('centro_produccion_id',$id)->with('_responsable')->get();

        return response()->success(compact('cocina'));
    }

    public function search(Request $request)
    {
        $centro = CentroProduccion::where('nombre','like',$request->data.'%')
            ->orWhere('descripcion','like',$request->data.'%')
            ->orWhere('direccion','like',$request->data.'%')
            ->orWhere('responsable','like',$request->data.'%')
            ->orWhere('tlf_responsable','like',$request->data.'%')
            ->orWhere('compania_id','like',$request->data.'%')
            ->with('compania')
            ->get();

        return response()->success(compact('centro'));
    }

    public function csv()
    {
        $centro = CentroProduccion::all();

        $csv = new \Laracsv\Export();

        $csv->build($centro, [
            'nombre',
            'direccion',
            'descripcion',
            'responsable',
            'responsable',
            'compania_id'
        ])->download('centros_de_produccion.csv');
    }

    public function pdf()
    {
        $centros = CentroProduccion::with('compania')->get();

        $pdf = PDF::loadView('PDF.centros_pdf',compact('centros'));

        return $pdf->download('centros_de_produccion.pdf');
    }
}
