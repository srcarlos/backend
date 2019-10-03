<?php

namespace App\Http\Controllers;

use App\Existencia;
use App\Insumo;
use App\MovimientoAjuste;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class MovimientoAjusteController extends Controller
{
    public function store(Request $request)
    {
        //dd($request->all());

        $this->validate($request,[
            'cocina_id' => 'required',
            'bodega_id' => 'required',
            'seccion_id' => 'required',
            'posicion_id' => 'required',
            'accion' => 'required',
            'tipo' => 'required',
        ]);

       //dd($request->insumos);

        if ($request->accion == 0) {
            $verificacion = $this->verificarExistencia($request);
            if (is_array($verificacion)) {
                $string = implode(', ', $verificacion);
                return response()->error('Imposible procesar el movimiento por deficiencia de los insumos: ' . $string);
            }
        }

        try{
            DB::beginTransaction();
            $movimiento = MovimientoAjuste::create($request->all());
            foreach ($request->insumos as $insumo){
                $movimiento->insumos()->attach($insumo['insumo_id'],['cantidad' => $insumo['cantidad']]);
            }
            $request->accion == 0 ? $this->egreso($request) : $this->ingreso($request);
            DB::commit();
            return response()->success('El movimiento se ha registrado exitosamente');
        }catch (\Exception $e){
            DB::rollBack();
            return response()->error('Ha ocurrido un error: '.$e->getMessage());
        }
    }

    private function verificarExistencia($request)
    {
        $_insumo = [];

        foreach ($request->insumos as $insumo){
            $stock = $this->existencia($request,$insumo);

            if(!$stock || $stock->cantidad < $insumo['cantidad']){
                $x = Insumo::find($insumo['insumo_id']);
                $_insumo[] = $x->nombre;
            }
        }

        return count($_insumo) > 0 ? $_insumo : true;
    }

    private function ingreso($request)
    {
        foreach ($request->insumos as $insumo) {

            $stock = $this->existencia($request,$insumo);

            if ($stock){
                $stock->cantidad += $insumo['cantidad'];
                $stock->save();
            }else{
                $x = Insumo::find($insumo['insumo_id']);
                Existencia::create([
                    'insumo_id' => $insumo['insumo_id'],
                    'bodega_id' => $request->bodega_id,
                    'seccion_id' => $request->seccion_id,
                    'posicion_id' => $request->posicion_id,
                    'cantidad' => $insumo['cantidad'],
                    'unidad' => $x->unidad_compra
                ]);
            }
        }
    }

    private function egreso($request)
    {
        foreach ($request->insumos as $insumo) {

            $stock = $this->existencia($request,$insumo);

            if ($stock->cantidad >= $insumo['cantidad'] ){
                $stock->cantidad -= $insumo['cantidad'];
            }else{
                $stock->cantidad = 0;
            }
            $stock->save();
        }
    }

    private function existencia($request,$insumo)
    {
        $stock = Existencia::where([
            'insumo_id' => $insumo['insumo_id'],
            'bodega_id' => $request->bodega_id,
            'seccion_id' => $request->seccion_id,
            'posicion_id' => $request->posicion_id,
        ])->first();

        return $stock;
    }

    public function index()
    {
        $movimiento_ajuste = MovimientoAjuste::with('bodega','seccion','posicion')->get();

        return response()->success(compact('movimiento_ajuste'));
    }

    public function show($id)
    {
        $movimiento_ajuste = MovimientoAjuste::where('id',$id)->with('cocina','bodega','seccion','posicion','insumos','insumos.unidad_compra')->first();

        return response()->success(compact('movimiento_ajuste'));
    }

    public function update(Request $request, $id)
    {
        $movimiento_ajuste = MovimientoAjuste::find($id);

        if(!$movimiento_ajuste){
            return response()->error('Movimiento no existe');
        }

        $movimiento_ajuste->observacion = $request->observacion;
        $movimiento_ajuste->save();

        return response()->success('Movimiento actualizado exitosamente');
    }
}
