<?php

namespace App\Http\Controllers;

use App\CentroProduccion;
use App\Existencia;
use App\Insumo;
use App\Orden;
use App\OrdenDetalle;
use App\OrdenHistorico;
use App\Cocina;
use App\Proveedor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\OrdenHistoricoController;

use App\Http\Requests;

class OrdenController extends Controller
{
    public function sugerirProveedor(Request $request)
    {
        $this->validate($request,[
            'detalles.*.insumo_id' => 'required',
            'detalles.*.cantidad' => 'required',
        ]);

        $_insumos = [];

        foreach ($request->insumos as $insumo) {

            $_insumo = Insumo::where('id', $insumo['insumo_id'])->with([
                'unidad_compra' => function($q){ $q->select('id','nombre'); },
                'proveedores' => function($q){ $q->select('nombre','apellido','tiempo_entrega')->orderBy('pivot_precio','asc')->orderBy('tiempo_entrega','asc'); }
            ])->first(['id','nombre','unidad_compra']);

            foreach ($_insumo->proveedores as $i){
                $i->pivot->cantidad = $insumo['cantidad'];
                $i->pivot->total = $i->pivot->precio * $insumo['cantidad'];
            }

            if ($_insumo) {
                $_insumos[] = $_insumo;
            }
        }

        return response()->success(compact('_insumos'));
    }

    public function show($id)
    {
        $orden = OrdenHistorico::where('id',$id)->with([
            "detalles" => function($q){ $q->select('id','insumo_id','unidad','precio_unitario','cantidad','total','orden_historico_id'); },
            "detalles.unidad_medida" => function($q){ $q->select('id','nombre'); },
            "detalles.insumo" => function($q){ $q->select('id','nombre'); },
        ])->select('id','planificacion_id','proveedor_id','descripcion','fecha','estatus')->first();

        if (!$orden){
            return response()->error('La orden no existe');
        }

        return response()->success(compact('orden'));
    }

    public function update(Request $request,$id)
    {
        $orden = Orden::find($id);

        if (!$orden){
            return response()->error('La orden no existe');
        }

        $this->validate($request,[
            'descripcion' => 'required',
            'fecha' => 'required',
            'detalles.*.insumo_id' => 'required',
            'detalles.*.unidad' => 'required',
            'detalles.*.proveedor_id' => 'required',
            'detalles.*.precio_unitario' => 'required',
            'detalles.*.cantidad' => 'required',
            'detalles.*.total' => 'required',
        ]);

        try{
            DB::transaction(function () use($request, $orden){
                $orden->descripcion = $request->descripcion;
                $orden->fecha = $request->fecha;
                $orden->save();
                $orden->detalles()->delete();
                $orden->detalles()->createMany($request->detalles);
            });
        }catch (\Exception $e){
            return response()->error('Error '.$e->getMessage());
        }
        
        return response()->success('La orden ha sido modificada exitosamente');
    }

    public function destroy($id)
    {
        $orden = Orden::find($id);

        if (!$orden){
            return response()->error('La orden no existe');
        }

        $orden->delete();

        return response()->success('La orden ha sido eliminada exitosamente');
    }

    public function index()
    {
        $orden = Orden::with("proveedor")->get();
        return response()->success(compact('orden'));
    }
}
