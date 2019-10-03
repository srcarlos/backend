<?php

namespace App\Http\Controllers;

use App\Existencia;
use App\MovimientoTransferencia;
use App\OrdenHistorico;
use App\TransferenciaInsumo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class TransferenciaController extends Controller
{
    public function store(Request $request)
    {
        $this->validator($request);

        foreach ($request->transferencias as $t) {

            $existencia = Existencia::where([
                'bodega_id' => $t['bodega_sal'],
                'posicion_id' => $t['posicion_sal'],
                'seccion_id' => $t['seccion_sal'],
                'insumo_id' => $t['insumo_sal']
            ])->first();

            if (!$existencia || $existencia->cantidad < $t['cantidad_sal']){
                return response()->error('La cantidad de insumo que desea transferir es mayor a su existencia o no existe');
            };
        }

        //$this->registrarExistencia($request->transferencias);

        try{
            DB::transaction(function () use($request){
                $movimiento = MovimientoTransferencia::create([
                    "orden_historico_id" => $request->orden_historico_id,
                    "observacion" => $request->observacion,
                ]);

                $movimiento->transferencias()->createMany($request->transferencias);

                if ($request->orden_historico_id){
                    $this->modifOC($request->orden_historico_id,$request->transferencias);
                }
            });
        }catch (\Exception $e){
            return response()->error('Error '.$e->getMessage());
        }

        return response()->success('Transferencia registrada exitosamente');
    }

    private function modifOC(int $orden, array $transferencias)
    {
        $ordenHist = OrdenHistorico::with('detalles')->where('id',$orden)->first();

        foreach ($ordenHist->detalles as $d) {
            foreach ($transferencias as $t) {
                if($d->insumo_id == $t['insumo_sal']){

                    if ($d->cantidad == $t['cantidad_sal']){
                        $d->estatus = "por transferir";
                        $d->save();
                    }else{
                        $cantidad = $d->cantidad - $t['cantidad_sal'];
                        $d->cantidad = $cantidad;
                        $d->total = $d->precio_unitario * $cantidad;
                        $d->save();

                        $ordenHist->detalles()->create([
                            "insumo_id" => $d->insumo_id,
                            "unidad" => $d->unidad,
                            "precio_unitario" => $d->precio_unitario,
                            "cantidad" => $t['cantidad_sal'],
                            "total" => $t['cantidad_sal'] * $d->precio_unitario,
                            "estatus" => "por transferir",
                            "orden_historico_id" => $d->orden_historico_id,
                        ]);
                    }
                }
            }
        }
    }

    private function validator($request)
    {
        $this->validate($request,[
            'observacion' => 'required',
            'transferencias.*.bodega_sal' => 'required',
            'transferencias.*.seccion_sal' => 'required',
            'transferencias.*.posicion_sal' => 'required',
            'transferencias.*.insumo_sal' => 'required',
            'transferencias.*.cantidad_sal' => 'required',
            'transferencias.*.unidad' => 'required',
            'transferencias.*.bodega_ent' => 'required',
            //'transferencias.*.seccion_ent' => 'required',
            //'transferencias.*.posicion_ent' => 'required',
            //'transferencias.*.movimientotransf_id' => 'required',
        ]);
    }

    public function destroy($id)
    {
        $transferencia = MovimientoTransferencia::find($id);

        if (!$transferencia){
            return response()->error('Transferencia no existe');
        }

        $transferencia->delete();

        return response()->success('Transferencia eliminada exitosamente');
    }

    public function show($id)
    {
        $transferencia = MovimientoTransferencia::where('id',$id)
            ->with('transferencias')
            ->with('transferencias.insumo')
            ->with('transferencias.bodegaSal')
            ->with('transferencias.seccionSal')
            ->with('transferencias.posicionSal')
            ->with('transferencias.bodegaEnt')
            ->with('transferencias.seccionEnt')
            ->with('transferencias.posicionEnt')
            ->with('transferencias.posicionEnt')
            ->with('transferencias.unidadMedida')
            ->select('id','observacion','created_at as fecha')
            ->first();

        if (!$transferencia){
            return response()->error('Transferencia no existe');
        }

        return response()->success(compact('transferencia'));
    }

    public function update(Request $request, $id)
    {
        $transferencia = MovimientoTransferencia::find($id);

        if (!$transferencia){
            return response()->error('Transferencia no existe');
        }

        $transferencia->observacion = $request->observacion;
        $transferencia->save();

        return response()->success('Transferencia actualizada exitosamente');
    }

    public function cambiarEstatus(Request $request,$id)
    {
        $transferencia = MovimientoTransferencia::find($id);

        if (!$transferencia){
            return response()->error('Transferencia no existe');
        }

        $ruta = explode('/',$request->path());

        $msj = '';

        try{
            DB::transaction(function() use($transferencia,$ruta,&$msj){
                if ($ruta[2] == "aprobar") {
                    $transferencia->estatus = "aprobada";
                    $msj = 'aprobada';
                }elseif ($ruta[2] == "conformar"){
                    $transferencia->estatus = "conformada";
                    $msj = 'conformada';
                }elseif ($ruta[2] == "confirmar"){
                    $transferencia->estatus = "confirmada";
                    $msj = 'confirmada';

                    $this->registrarExistencia($transferencia);
                }
            });
        }catch (\Exception $e){
            return response()->error("Error: {$e->getMessage()}");
        }

        $transferencia->save();

        return response()->success("Transferencia ha sido {$msj} exitosamente");
    }

    private function registrarExistencia(Model $transferencia)
    {
        $data = $transferencia->transferencias;

        foreach ($data as $t) {

            $existencia = Existencia::where([
                'bodega_id' => $t['bodega_sal'],
                'posicion_id' => $t['posicion_sal'],
                'seccion_id' => $t['seccion_sal'],
                'insumo_id' => $t['insumo_sal']
            ])->first();

            if($existencia->cantidad == $t['cantidad_sal']){
                $existencia->delete();
            }else{
                $existencia->cantidad -= $t['cantidad_sal'];
                $existencia->save();
            }

            $existencia = Existencia::where([
                'bodega_id' => $t['bodega_ent'],
                'posicion_id' => $t['posicion_ent'],
                'seccion_id' => $t['seccion_ent'],
                'insumo_id' => $t['insumo_sal']
            ])->first();

            if (!$existencia){
                Existencia::create([
                    'bodega_id' => $t['bodega_ent'],
                    'seccion_id' => $t['seccion_ent'],
                    'posicion_id' => $t['posicion_ent'],
                    'insumo_id' => $t['insumo_sal'],
                    'cantidad' => $t['cantidad_sal'],
                    'unidad' => $t['unidad'],
                ]);
            }else{
                $existencia->cantidad += $t['cantidad_sal'];
                $existencia->save();
            }
        }
    }



}
