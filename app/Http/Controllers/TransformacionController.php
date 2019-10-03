<?php

namespace App\Http\Controllers;

use App\Existencia;
use App\ExistenciaTrans;
use App\Insumo;
use App\InsumoTransInsumo;
use App\MovimientoTransformacion;
use App\TransformacionHistorico;
use App\Posicion;
use Illuminate\Http\Request;
use DB;
use App\BaseInsumoTransformado;
use App\Http\Requests;

class TransformacionController extends Controller
{

    public function store(Request $request)
    {
//        dd($request->all());

        $this->validator($request);

        try{
            DB::transaction(function() use($request){
                $this->registrarInsumotrans($request);
                $this->descontarExistencia($request->stock);
            });
        }catch (\Exception $e){
            return response()->error('Se ha producido un error: '.$e->getMessage());
        }


        return response()->success('Transformacion realizada exitosamente');
    }

    private function registrarInsumotrans(Request $request)
    {
        //dd($request->all());

        $movimiento = MovimientoTransformacion::create([
            "bodega_id" => $request->bodega,
            "seccion_id" => $request->seccion,
            "posicion_id" => $request->posicion,
            "fecha" => $request->fecha,
            "observacion" => $request->observacion,
            "insumotrans_id" => $request->insumotrans_id,
            "cantidad" => $request->cantidad,
            "unidad" => $request->unidad,
        ]);

        $this->crearHistorico($request->stock,$movimiento->id);

        $posicion = Posicion::find($request->posicion);

        $existenciaT = Existencia::where([
            "bodega_id" => $request->bodega,
            "seccion_id" => $request->seccion,
            "posicion_id" => $request->posicion,
            'insumo_id' => $request->insumotrans_id,
        ])->first();

        if(!$existenciaT){
            $posicion->insumos()->attach($request->insumotrans_id,[
                    "bodega_id" => $request->bodega,
                    "seccion_id" => $request->seccion,
                    "posicion_id" => $request->posicion,
                    'cantidad' => $request->cantidad,
                    'unidad' => $request->unidad,
                    'movimientotrans_id' => $movimiento->id,
                ]
            );
        }else{
            $existenciaT->cantidad += $request->cantidad;
            $existenciaT->save();
        }
    }

    private function descontarExistencia(array $stock)
    {
        foreach ($stock as $st) {

            $existencia = Existencia::where([
                'insumo_id' => $st['insumo_id'],
                'posicion_id' => $st['posicion_id'],
                'seccion_id' => $st['seccion_id'],
                'bodega_id' => $st['bodega_id'],
            ])->first();

            $existencia->cantidad -= $st['necesario'];
            $existencia->save();
        }
    }

    private function crearHistorico(array $stock,$movimiento)
    {
        foreach ($stock as $st) {

            TransformacionHistorico::create([
                'insumo_id' => $st['insumo_id'],
                'seccion_id' => $st['seccion_id'],
                'posicion_id' => $st['posicion_id'],
                'disponibilidad' => $st['cantidad'],
                'cant_req' => $st['necesario'],
                'unidad' => $st['unidad']['id'],
                'movimiento_transformacion' => $movimiento,
            ]);
        }
    }

    private function validator($request)
    {
        $this->validate($request,[
            "bodega" => "required",
            "seccion" => "required",
            "posicion" => "required",
            "fecha" => "required",
            "observacion" => "required",
            "insumotrans_id" => "required",
            "cantidad" => "required",
            //"unidad" => "required",
            "insumos.*.insumo_id" => "required",
            "insumos.*.posicion_id" => "required",
            "insumos.*.seccion_id" => "required",
            "insumos.*.bodega_id" => "required",
            "insumos.*.unidad" => "required",
        ]);
    }

    public function show($id)
    {
        $transformacion = MovimientoTransformacion::where('id',$id)->with(
            'insumo_transformado.unidad_produccion',
            'bodega',
            'seccion',
            'posicion',
            'historicos.insumo',
            'historicos.seccion',
            'historicos.posicion',
            'historicos.unidad_medida'
        )->first();

        return response()->success(compact('transformacion'));
    }

    public function update(Request $request,$id)
    {
        $transformacion = MovimientoTransformacion::find($id);

        if(!$transformacion) {
            return response()->error('Transformacion no existe');
        }

        $transformacion->observacion = $request->observacion;
        $transformacion->save();

        return response()->success('Transformacion actualizada exitosamente');
    }

    public function destroy($id)
    {
        $transformacion = ExistenciaTrans::find($id);

        if(!$transformacion) {
            return response()->error('Transformacion no existe');
        }

        $movimientot = MovimientoTransformacion::find($transformacion->movimientotrans_id);
        $movimientot->delete();

        return response()->success('Transformacion eliminada exitosamente');
    }

    public function index()
    {
        $transformacion = MovimientoTransformacion::with('bodega','seccion','posicion','insumo_transformado')->get();

        return response()->success(compact('transformacion'));
    }
}
