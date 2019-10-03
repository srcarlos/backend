<?php

namespace App\Http\Controllers;

use App\Credito;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Factura;
use Illuminate\Support\Facades\DB;
use Storage;
use App\Cotizacion;
use Validator;

class FacturaController extends Controller
{
    public function store(Request $request)
    {
//        dd($request->all());

        $this->validate($request,[
            'cliente_id' => "required",
            'sub_total' => "required|numeric",
            'porcentaje_descuento' => "required|numeric",
            'descuento_total' => "required|numeric",
            'total' => "required|numeric",
            'iva' => "required|numeric",
        ]);

        $data['planes'] = json_decode($request->planes,true);

        $validator = Validator::make($data,[
            'planes.*.beneficiario' => 'required',
            'planes.*.cantidad' => 'required',
            'planes.*.fecha_activacion' => 'required',
            'planes.*.fecha_expiracion' => 'required',
        ]);

        if ($validator->fails()){
            return response()->error('Todos los planes deben tener un beneficiario asignado.');
        }

        try{

            DB::beginTransaction();

            $factura = Factura::create($request->all());

            foreach (json_decode($request->planes) as $plan){

                $factura->planes()->attach($plan->plan_id,
                    [
                        'cliente_id' => $request->cliente_id,
                        'beneficiario' => $plan->beneficiario,
                        'fecha_activacion' => $plan->fecha_activacion,
                        'fecha_expiracion' => $plan->fecha_expiracion,
                        'cantidad' => $plan->cantidad,
                    ]
                );
            }

            if ($request->metodos_pago){
                foreach (json_decode($request->metodos_pago) as $metodo){
                    if($metodo->type == 'credito'){
                        $credito = json_decode($request->credito);

                        foreach ($credito as $c){
                            Credito::create([
                                'dias' => $c->dias_credito,
                                'fecha_expiracion' => $c->fecha_expiracion,
                                'cotizacion_id' => $request->cotizacion_id ? $request->cotizacion_id : null,
                                'cliente_id' => $request->cliente_id,
                                'factura_id' => $factura->id,
                            ]);
                        }
                    }else{
                        $factura->metodos()->attach($request->metodos);

                        if($metodo->type == 'cheque'){
                            $this->cheque($factura,$metodo);
                        }
                        if($metodo->type == 'deposito'){
                            $this->deposito($factura,$metodo);
                        }
                        if($metodo->type == 'transferencia'){
                            $this->transferencia($factura,$metodo);
                        }
                        if($metodo->type == 'tarjeta_credito'){
                            $this->tarjeta_credito($factura,$metodo);
                        }
                    }
                }
            }


            //Si parte de una cotizacion,
            //al crearse la factura se cambia estatus de cotizacion a
            //pagada
            if($request->cotizacion_id){
                $cotizacion = Cotizacion::find($request->cotizacion_id);
                $cotizacion->estatus = "1";
                $cotizacion->save();
            }

            if(count($_FILES) > 0){
                $c = 1;
                foreach ($request->files as $comprobante){
                    $factura = Factura::find($factura->id);
                    Storage::put('public/comprobantes/C'.$request->cotizacion_id.'/'.$c.'.'.$comprobante->getClientOriginalExtension(),
                        file_get_contents($comprobante->getRealPath()));
                    $c++;
                }
            }

            $factura->save();

            DB::commit();

            return response()->success('Factura creada exitosamente');

        }catch (\Exception $e){

            DB::rollBack();

            return response()->error('Se ha producido un error: '.$e->getMessage());
        }
    }

    private function cheque($factura, $cheque)
    {
        $factura->cheque()->create([
            'numero' => $cheque->numero,
            'banco' => $cheque->banco,
            'monto' => $cheque->monto,
            //'comprobante' => $cheque->file('cheque') ? $cheque->numero.'.'.$cheque->file('cheque')->extension() : null,
        ]);
        /*if($cheque->hasFile('cheque')){
            $this->comprobante($cheque,$cheque->numero);
        }*/
    }
    private function deposito($factura,$deposito)
    {
        //dd($deposito);
        $factura->deposito()->create([
            'monto' => $deposito->monto,
            'banco' => $deposito->banco,
            'dep_nro' => $deposito->dep_nro,
            'cta_nro' => $deposito->cta_nro,
            //'comprobante' => $deposito->hasFile('deposito') ? $deposito['numero'].'.'.$deposito->file('deposito')->extension() : null,
        ]);
        /*  if($deposito->hasFile('deposito')){
              $this->comprobante($deposito,$deposito->dep_nro);
          }*/

    }
    private function transferencia($factura, $transferencia)
    {
        $factura->transferencia()->create([
            'numero' => $transferencia->numero,
            'banco' => $transferencia->banco,
            'titular' => $transferencia->titular,
            'tipo_cuenta' => $transferencia->tipo_cuenta,
            'cta_nro' => $transferencia->cta_nro,
            'monto' => $transferencia->monto,
            //'comprobante' => $transferencia->hasFile('transferencia') ? $transferencia->numero.'.'.$transferencia->file('transferencia')->extension() : null,
        ]);
        /* if($transferencia->hasFile('transferencia')){
             $this->comprobante($transferencia,$transferencia->numero);
         }*/

    }
    private function tarjeta_credito($factura, $tarjeta_credito)
    {
        $factura->tarjeta_credito()->create([
            'titular' => $tarjeta_credito->titular,
//            'tipo_tarjeta' => $tarjeta_credito->tipo_tarjeta,
            'marca' => $tarjeta_credito->marca,
            'banco' => $tarjeta_credito->banco,
            'forma_pago' => $tarjeta_credito->forma_pago,
            'monto' => $tarjeta_credito->monto,
//            'nro_op' => $tarjeta_credito->nro_op,
            //'comprobante' => $tarjeta_credito->hasFile('tarjeta_credito') ? $tarjeta_credito->nro_op.'.'.$tarjeta_credito->file('tarjeta_credito')->extension() : null,
        ]);
        /* if($tarjeta_credito->hasFile('tarjeta_credito')){
             $this->comprobante($tarjeta_credito,$tarjeta_credito->nro_op);
         }*/
    }

    private function comprobante($request,$id)
    {
        Storage::put('public/comprobantes/'.$id.'.'.$request->file('comprobante')->extension(),
            file_get_contents($request->file('comprobante')->getRealPath()));
    }

    public function show($id)
    {
        $factura = Factura::all();

        return response()->success(compact('factura'));
    }
}
