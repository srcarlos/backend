<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\FacturaPlan;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use DB;

class FacturaPlanController extends Controller
{
    public function show($id)
    {
        $factura = FacturaPlan::where('id',$id)
            ->with('plan')
            ->with('factura')
            ->with('cliente')
            ->with('beneficiario')
            ->get();
        if (!$factura){
            return response()->json('El plan de factura no existe',404);
        }
        return response()->success(compact('factura'));
    }

    public function index()
    {
        $factura = FacturaPlan::with('plan')
            ->with('factura')
            ->with('cliente')
            ->with('beneficiario')
            ->get();

        return response()->success(compact('factura'));
    }

    public function planificar(Request $request)
    {
      $this->validate($request,[
        'desde' => 'required|date',
        'hasta' => 'required|date'
      ]);
      
  
      $period = CarbonPeriod::create(Carbon::parse($request->desde)->format('Y-m-d'), Carbon::parse($request->hasta)->format('Y-m-d'));
   
    

       $fechas= [];
        foreach ($period as $date) {

        $planes = FacturaPlan::with(['plan','plan.turnos'])
        ->with('factura')
        ->with('cliente')
        ->with('beneficiario')
        ->whereDate('fecha_activacion','<=', $date->format('Y-m-d')   )
        ->whereDate('fecha_expiracion','>=', $date->format('Y-m-d')   )
        ->get();


        if(count($planes)){

          foreach ($planes as $plan) {
            $cantidad =0;
            foreach ($plan["plan"]["turnos"] as $turno) {
              $cantidad = $cantidad + $turno["cantidad"];
            }
             $plan["total_platos"] = $cantidad ;
           }
          
           $row = ["dia" => $date->format('Y-m-d') , "planes" => $planes ];
           $fechas[] =  $row ;
         }
       }//foreach

        return response()->success(compact('fechas'));
    }

    public function estado(Request $request, $id) {
      $fPlan = FacturaPlan::find($id);
      if (!$fPlan) {
        return response()->json('El plan de factura no existe',404);
      }
      $fPlan->estado = $request->estado === true || $request->estado == 1;
      $fPlan->save();
      return response()->success("Se cambio el estado del plan exitosamnete.");
    }

    public function habilitar(Request $request,$id) {
      $fPlan = FacturaPlan::find($id);
      if (!$fPlan) {
        return response()->json('El plan de factura no existe',404);
      }
      $fPlan->suspender_desde = null;
      $fPlan->suspender_hasta = null;
      if ($request->has('estado')) {
        $fPlan->estado = $request->estado === true || $request->estado == 1;
      }
      $fPlan->save();
      return response()->success("Plan habilitado exitosamente");
    }

    public function suspender(Request $request,$id) {
      $fPlan = FacturaPlan::find($id);
      if (!$fPlan) {
        return response()->json('El plan de factura no existe',404);
      }
      $this->validate($request,[
        'suspender_desde' => 'required|date',
        'suspender_hasta' => 'required|date'
      ]);
      if ($request->has('estado')) {
        $fPlan->estado = $request->estado === true || $request->estado == 1;
      }
      $fPlan->suspender_desde = $request->suspender_desde;
      $fPlan->suspender_hasta = $request->suspender_hasta;
      $fPlan->save();
      return response()->success("Plan suspendido exitosamente.");

    }

    public function activar(Request $request,$id) {
      $fPlan = FacturaPlan::find($id);
      if (!$fPlan) {
        return response()->json('El plan de factura no existe',404);
      }
      $this->validate($request,[
        'fecha_activacion' => 'required|date',
        'fecha_expiracion' => 'required|date'
      ]);
      $fPlan->fecha_activacion = $request->fecha_activacion;
      $fPlan->fecha_expiracion = $request->fecha_expiracion;
      if ($request->habilitar) {
        $fPlan->suspender_desde = null;
        $fPlan->suspender_hasta = null;
      }
      if ($request->has('estado')) {
        $fPlan->estado = $request->estado === true || $request->estado == 1;
      }
      $fPlan->save();
      return response()->success("Plan activado exitosamente.");
    }
}
