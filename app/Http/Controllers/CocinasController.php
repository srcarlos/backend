<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Cocina;
use App\MovimientoAjuste;
use Barryvdh\DomPDF\Facade as PDF;

class CocinasController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'nombre' => 'required',
            'direccion' => 'required',
            'responsable' => 'required',
            'centro_produccion_id' => 'required',
        ]);

        Cocina::create($request->all());

        return response()->success("Cocina creada exitosamente");
    }

    public function show($id)
    {
        $cocina = Cocina::with('_responsable','centro')->where('id',$id)->first();

        if (!$cocina){
            return response()->json('La cocina no existe',404);
        }

        return response()->success(compact('cocina'));
    }

    public function update(Request $request,$id)
    {
        $cocina = Cocina::find($id);

        if (!$cocina){
            return response()->json('La cocina no existe',404);
        }

        $this->validate($request,[
            'nombre' => 'required',
            'direccion' => 'required',
            'responsable' => 'required',
            'centro_produccion_id' => 'required',
        ]);

        $cocina->nombre = $request->nombre;
        $cocina->direccion = $request->direccion;
        $cocina->centro_produccion_id = $request->centro_produccion_id;
        $cocina->responsable = $request->responsable;
        $cocina->save();

        return response()->success('La cocina ha sido modificada exitosamente');
    }

    public function destroy($id)
    {
        $cocina = Cocina::find($id);

        if (!$cocina){
            return response()->json('La cocina no existe',404);
        }

        $cocina->delete();

        return response()->success('La cocina ha sido eliminada exitosamente');
    }

    public function index()
    {
        $cocina = Cocina::with('_responsable')->get();

        return response()->success(compact('cocina'));
    }


    public function bodegasByCocina($id)
    {
        $cocina = Cocina::find($id);
        $bodega = $cocina->bodegas;

        return response()->success(compact('bodega'));
    }

    public function ajustesByCocina($id)
    {
        $movimiento_ajuste = MovimientoAjuste::with('bodega','seccion','posicion')->where('cocina_id',$id)->get();

        return response()->success(compact('movimiento_ajuste'));
    }

    public function search(Request $request)
    {
        $cocina = Cocina::where('nombre','like',$request->data.'%')
            ->orWhere('direccion','like',$request->data.'%')
            ->orWhere('responsable','like',$request->data.'%')
            ->with('centro')
            ->get();

        return response()->success(compact('cocina'));
    }

    public function csv()
    {
        $cocinas = Cocina::with('centro','_responsable')->get();

        $csv = new \Laracsv\Export();

        $csv->build($cocinas, [
            'nombre' => 'Nombre',
            'direccion' => 'Nombre',
            'responsable' => 'Responsable',
            'centro.nombre' => 'Centro',
        ])->download('cocinas.csv');
    }

    public function pdf()
    {
        $cocinas = Cocina::with('centro','_responsable')->get();

        $pdf = PDF::loadView('PDF.cocinas_pdf',compact('cocinas'));

        return $pdf->download('cocinas.pdf');
    }
}
