<?php

namespace App\Http\Controllers;

use App\Existencia;
use App\ExistenciaTrans;
use App\MovimientoTransferencia;
use App\MovimientoTransformacion;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Bodega;
use App\Insumo;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;

class BodegasController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'codigo' => 'required|unique:bodegas,codigo',
            'nombre' => 'required',
            'ubicacion' => 'required',
            'cocina_id' => 'required|numeric',
            'tipo' => 'required',
        ]);

        Bodega::create($request->all());

        return response()->success("Bodega creada exitosamente");
    }

    public function show($id)
    {
        $bodega = Bodega::find($id);

        if (!$bodega){
            return response()->json('La bodega no existe',404);
        }

        return response()->success(compact('bodega'));
    }

    public function update(Request $request,$id)
    {
        $bodega = Bodega::find($id);

        if (!$bodega){
            return response()->json('La bodega no existe',404);
        }

        $this->validate($request,[
            'codigo' => 'required|unique:bodegas,codigo,'.$bodega->id,
            'nombre' => 'required',
            'ubicacion' => 'required',
            'cocina_id' => 'required|numeric',
            'tipo' => 'required',
        ]);

        $bodega->codigo = $request->codigo;
        $bodega->nombre = $request->nombre;
        $bodega->ubicacion = $request->ubicacion;
        $bodega->cocina_id = $request->cocina_id;
        $bodega->tipo = $request->tipo;
        $bodega->save();

        return response()->success('La bodega ha sido modificada exitosamente');
    }

    public function destroy($id)
    {
        $bodega = Bodega::find($id);

        if (!$bodega){
            return response()->json('La bodega no existe',404);
        }

        $bodega->delete();

        return response()->success('La bodega ha sido eliminada exitosamente');
    }

    public function index()
    {
        $bodega = Bodega::all();

        return response()->success(compact('bodega'));
    }

    public function seccionesByBodega($id)
    {
        $bodega = Bodega::find($id);
        $seccion = $bodega->secciones;

        return response()->success(compact('seccion'));
    }

    public function search(Request $request)
    {
        $bodega = Bodega::where('codigo','like',$request->data.'%')
            ->orWhere('nombre','like',$request->data.'%')
            ->orWhere('ubicacion','like',$request->data.'%')
            ->orWhere('tipo','like',$request->data.'%')
            ->with('cocina')
            ->get();

        return response()->success(compact('bodega'));
    }

    public function existencia($bodegaId,$insumoId)
    {
        $existencia = Existencia::with(['bodega','seccion','posicion','insumo','insumo.unidad_compra'])
            ->where(['bodega_id' => $bodegaId,'insumo_id' => $insumoId])
            ->get();

        return response()->success(compact('existencia'));
    }

    public function insumosGET($id)
    {
        $insumos = DB::table('existencias')
            ->join('insumos','existencias.insumo_id','=','insumos.id')
            ->join('bodegas','existencias.bodega_id','=','bodegas.id')
            ->join('secciones','existencias.seccion_id','=','secciones.id')
            ->join('posiciones','existencias.posicion_id','=','posiciones.id')
            ->join('unidad_medidas','existencias.unidad','=','unidad_medidas.id')
            ->where('existencias.bodega_id','=',$id)
            ->select(
                'existencias.id',
                'insumos.nombre as insumo',
                'unidad_medidas.nombre as medida',
                'existencias.cantidad',
                'secciones.nombre as seccion',
                'bodegas.nombre as bodega',
                'posiciones.nombre as posicion'
            )
            ->get();

        return response()->success(compact('insumos'));
    }

    public function insumosEnBodega($id)
    {
        $insumos = Existencia::where('bodega_id',$id)->get();

        $_insumos = [];

        foreach ($insumos as $insumo){
            $i = Insumo::where('id',$insumo->insumo_id)->with('unidad_compra')->first();
            if (!in_array($i,$_insumos)){
                array_push($_insumos,$i);
            }
        }

        $insumos = $_insumos;

        return response()->success(compact('insumos'));
    }

    //Metodo temporal para pruebas de listado
    public function insumosPOST(Request $request,$id)
    {
        $bodega = Bodega::find($id);
        foreach ($request->insumos as $insumo)
        $bodega->insumos()->attach($insumo['insumo_id'],[
            'posicion_id' => $insumo['posicion_id'],
            'bodega_id' => $insumo['bodega_id'],
            'seccion_id' => $insumo['seccion_id'],
            'cantidad' => $insumo['cantidad'],
            'unidad' => $insumo['unidad'],
        ]);

        return response()->success('Insumos almacenados');
    }

    public function filtrarInsumos(Request $request,$id)
    {
        $insumos = Insumo::where('nombre','like',$request->data.'%')
            ->orWhereHas('bodegas',function ($query) use($request){
                $query->where('nombre','like',$request->data.'%')
                    ->orWhere('cantidad','like',$request->data.'%');
            })
            ->orWhereHas('posiciones',function ($query) use($request){
                $query->where('nombre','like',$request->data.'%');
            })
            ->orWhereHas('posiciones.seccion',function ($query) use($request){
                $query->where('nombre','like',$request->data.'%');
            })
            ->orWhereHas('unidad_produccion',function ($query) use($request){
                $query->where('nombre','like',$request->data.'%');
            })
            ->with('posiciones.seccion')
            ->with('bodegas')
            ->with('unidad_produccion')
            ->get();

        return response()->success(compact('insumos'));
    }

    public function transformaciones($id)
    {
        $transformacion = MovimientoTransformacion::where('bodega_id',$id)->with('bodega','seccion','posicion','insumo_transformado')->get();

        return response()->success(compact('transformacion'));
    }

    public function transferencias($id)
    {
        $transferencias = MovimientoTransferencia::whereHas('transferencias',function ($q) use($id){
            $q->where('bodega_sal',$id);
        })->get();

        return response()->success(compact('transferencias'));
    }

    public function csv()
    {
        $bodega = Bodega::with('cocina')->get();

        $csv = new \Laracsv\Export();

        $csv->build($bodega,
            [
            'codigo' => 'Codigo',
            'nombre' => 'Nombre',
            'ubicacion' => 'Ubicación',
            'tipo' => 'Tipo',
            'cocina.nombre' => 'Cocina',
            ]
        )->download('bodegas.csv');
    }

    public function pdf()
    {
        $bodegas = Bodega::all();

        $pdf = PDF::loadView('PDF.bodegas_pdf',compact('bodegas'));

        return $pdf->download('bodegas.pdf');
    }
}
