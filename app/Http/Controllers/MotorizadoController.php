<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Motorizado;
use Barryvdh\DomPDF\Facade as PDF;

class MotorizadoController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'nombre' => 'required|unique:motorizados,nombre',
            'estado' => 'required|boolean'
        ]);

        Motorizado::create($request->all());

        return response()->success("Motorizado creado exitosamente");
    }

    public function show($id)
    {
        $motorizado = Motorizado::find($id);

        if (!$motorizado){
            return response()->json('El motorizado de pago no existe',404);
        }

        return response()->success(compact('motorizado'));
    }

    public function update(Request $request,$id)
    {
        $motorizado = Motorizado::find($id);

        if (!$motorizado){
            return response()->json('El motorizado no existe',404);
        }

        $this->validate($request,[
            'nombre' => 'required|unique:motorizados,nombre,'.$motorizado->id,
            'estado' => 'required|boolean'
        ]);

        $motorizado->nombre = $request->nombre;
        $motorizado->estado = $request->estado;
        $motorizado->save();

        return response()->success('El motorizado ha sido modificado exitosamente');
    }

    public function destroy($id)
    {
        $motorizado = Motorizado::find($id);

        if (!$motorizado){
            return response()->json('El motorizado no existe',404);
        }

        $motorizado->delete();

        return response()->success('El motorizado ha sido eliminado exitosamente');
    }

    public function index()
    {
        $motorizado = Motorizado::all();
        foreach ($motorizado as $value){
            $value->estado = ($value->estado == 1) ? 'Activo' : 'Inactivo';
        }
        return response()->success(compact('motorizado'));
    }

    public function search(Request $request)
    {
        $motorizado = Motorizado::where('nombre','like',$request->data.'%')
            ->orWhere('estado','like',$request->data.'%')
            ->get();

        return response()->success(compact('motorizado'));
    }

    public function csv()
    {
        $motorizado = Motorizado::all();

        $csv = new \Laracsv\Export();

        $csv->build($motorizado,
            [
                'id' => 'ID',
                'nombre' => 'Nombre',
                'estado' => 'Estado'
            ]
        )->download('motorizados.csv');
    }

    public function pdf()
    {
        $motorizados = Motorizado::all();

        $pdf = PDF::loadView('PDF.motorizados_pdf',compact('motorizados'));

        return $pdf->download('motorizados.pdf');
    }
}
