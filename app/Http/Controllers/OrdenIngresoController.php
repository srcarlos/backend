<?php

namespace App\Http\Controllers;

use App\Existencia;
use App\Orden;
use App\OrdenIngreso;
use App\OrdenHistorico;

use App\OrdenIngresoDetalle;
use App\PlanificacionProduccion;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class OrdenIngresoController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            "orden_compra_id" => "required",
            "ingresos.*.bodega_id" => "required",
            "ingresos.*.insumos.*.insumo_id" => "required",
            "ingresos.*.insumos.*.cantidad" => "required",
        ]);
        if($request->planificacion_id){
            $oc = OrdenHistorico::find($request->orden_compra_id);

        }else {
            $oc = OrdenHistorico::find($request->orden_compra_id);

        }

        if(!$oc){
            return response()->error('La orden de compra no existe');
        }

        DB::transaction(function () use($request){

            foreach ($request->ingresos as $ingreso) {

                    $orden_ingreso = OrdenIngreso::create([
                        "orden_compra_id" => $request->orden_compra_id,
                        "orden_produccion_id" => $request->orden_produccion_id,
                        "bodega_id" => $ingreso['bodega_id'],
                    ]);

                    foreach ($ingreso['insumos'] as $i) {

                        $orden_ingreso->detalles()->create([
                            "insumo_id" => $i['insumo_id'],
                            "unidad" => $i['unidad'],
                            "precio_unitario" => $i['precio_unitario'],
                            "cantidad" => $i['cantidad'],
                            "total" => $i['cantidad'] * $i['precio_unitario'],
                            "cantidad_ingresada" => $i['cantidad_ingresada'],
                        ]);
                    }
            }
        });

        $oc->estatus = 'completada';
        $oc->save();

        return response()->success('Ingresos registrados');
    }

    public function index()
    {
        $orden_ingreso = OrdenIngreso::with(['bodega' => function ($q) { $q->select('id','nombre'); }])->get();

        return response()->success(compact('orden_ingreso'));
    }

    public function show($id)
    {
        $orden_ingreso = OrdenIngreso::with([
            'bodega' => function ($q) { $q->select('id','nombre'); },
            'detalles' => function ($q) { $q->select('id','orden_ingreso_id','insumo_id','unidad','precio_unitario','cantidad','total','cantidad_ingresada','cantidad_ingresada','cantidad_recibida','seccion_id','posicion_id'); },
            'detalles.insumo' => function ($q) { $q->select('id','nombre'); },
            'detalles.unidad_medida' => function ($q) { $q->select('id','nombre'); },
            'detalles.seccion' => function ($q) { $q->select('id','nombre'); },
            'detalles.posicion' => function ($q) { $q->select('id','nombre'); },
            ])->where('id',$id)
              ->get(['id','orden_compra_id','bodega_id','estatus']);

        return response()->success(compact('orden_ingreso'));
    }

    public function distribuirInsumos(Request $request,$id)
    {
        $this->validate($request,[
            //"orden_ingreso_id" => "required",
            "detalles.*.id" => "required",
            "detalles.*.cantidad_recibida" => "required",
            "detalles.*.seccion_id" => "required",
            "detalles.*.posicion_id" => "required",
        ]);

        $orden_ingreso = OrdenIngreso::where(['id' => $id,'estatus' => 'recibido'])->first();

        //dd($orden_ingreso->toArray());

        if(!$orden_ingreso){
            return response()->error('La orden de ingreso no existe o ya fue sido distribuida');
        }

        DB::transaction(function () use($request,$orden_ingreso){

            foreach ($request->detalles as $detalle) {
                $_detalle = $orden_ingreso->detalles()->where('id',$detalle['id'])->first();
                //dd($_detalle->toArray());
                $_detalle->cantidad_recibida = $detalle['cantidad_recibida'];
                $_detalle->seccion_id = $detalle['seccion_id'];
                $_detalle->posicion_id = $detalle['posicion_id'];
                $_detalle->save();
            }

            $orden_ingreso->estatus = "distribuida";
            $orden_ingreso->save();
        });

        return response()->success('Insumos en orden de ingreso han sido distribudos');
    }

    public function altaDeInsumosOC($id)
    {
        $orden_ingreso = OrdenIngreso::with('detalles')
            ->where('id',$id)
            ->WhereNotIn('estatus',['recibido','confirmada'])
            ->first();

        if(!$orden_ingreso){
            return response()->error('La Orden de ingreso no existe, no ha sido distribuida o ya fue confirmada');
        }

        DB::transaction(function () use($orden_ingreso) {
            foreach ($orden_ingreso->detalles as $detalle ){

                $existencia = Existencia::where([
                    "bodega_id" => $orden_ingreso->bodega_id,
                    "seccion_id" => $detalle['seccion_id'],
                    "posicion_id" => $detalle['posicion_id'],
                    "insumo_id" => $detalle['insumo_id'],
                ])->first();

                if ($existencia){
                    $existencia->cantidad += $detalle['cantidad_recibida'];
                    $existencia->save();
                }else{
                    Existencia::create([
                        "bodega_id" => $orden_ingreso->bodega_id,
                        "seccion_id" => $detalle['seccion_id'],
                        "posicion_id" => $detalle['posicion_id'],
                        "insumo_id" => $detalle['insumo_id'],
                        "unidad" => $detalle['unidad'],
                        "cantidad" => $detalle['cantidad_recibida'],
                    ]);
                }
            }
            $orden_ingreso->estatus = "confirmada";
            $orden_ingreso->save();

            $orden_produccion = PlanificacionProduccion::where('id',$orden_ingreso->orden_produccion_id)->with([
                'ordenes_ingreso' => function($q) { $q->where('estatus','!=','confirmada'); }
            ])->first();

            /*if (count($orden_produccion->ordenes_ingreso) == 0){
                $orden_produccion->status = 1;
                $orden_produccion->save();
            }*/
        });

        return response()->success('Orden de ingreso confirmada');
    }

    /*public function reporte($id)
    {
        $orden_ingreso = OrdenIngreso::with([
            'bodega' => function ($q) { $q->select('id','nombre'); },
            'detalles' => function ($q) { $q->select('id','orden_ingreso_id','insumo_id','unidad','cantidad_recibida','seccion_id','posicion_id'); },
            'detalles.insumo' => function ($q) { $q->select('id','nombre'); },
            'detalles.unidad_medida' => function ($q) { $q->select('id','nombre'); },
            'detalles.seccion' => function ($q) { $q->select('id','nombre'); },
            'detalles.posicion' => function ($q) { $q->select('id','nombre'); },
        ])->where('id',$id)
            ->first(['id','orden_compra_id','bodega_id','estatus']);

        $pdf = PDF::loadView('PDF.ordenIngreso_pdf',compact('orden_ingreso'));

        return $pdf->download('orden_ingreso.pdf');
    }*/


}
