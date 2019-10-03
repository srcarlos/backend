<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\ClientePlan;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use DB;

class ClientePlanController extends Controller
{
    public function show($id)
    {
        $cliente = ClientePlan::where('id',$id)
            ->with('plan')
            ->with('cliente')
            ->with('beneficiario')
            ->get();
        if (!$cliente){
            return response()->json('El plan de cliente no existe',404);
        }
        return response()->success(compact('cliente'));
    }

    public function index()
    {
        $cliente = ClientePlan::with('plan')
            ->with('cliente')
            ->with('beneficiario')
            ->get();

        return response()->success(compact('cliente'));
    }

    public function estado(Request $request, $id) {
      $cPlan = ClientePlan::find($id);
      if (!$cPlan) {
        return response()->json('El plan de cliente no existe',404);
      }
      $cPlan->estado = $request->estado === true || $request->estado == 1;
      $cPlan->save();
      return response()->success("Se cambio el estado del plan exitosamnete.");
    }

    public function habilitar(Request $request,$id) {
      $cPlan = ClientePlan::find($id);
      if (!$cPlan) {
        return response()->json('El plan de cliente no existe',404);
      }
      $cPlan->suspender_desde = null;
      $cPlan->suspender_hasta = null;
      if ($request->has('estado')) {
        $cPlan->estado = $request->estado === true || $request->estado == 1;
      }
      $cPlan->save();
      return response()->success("Plan habilitado exitosamente");
    }

    public function suspender(Request $request,$id) {
      $cPlan = ClientePlan::find($id);
      if (!$cPlan) {
        return response()->json('El plan de cliente no existe',404);
      }
      $this->validate($request,[
        'suspender_desde' => 'required|date',
        'suspender_hasta' => 'required|date'
      ]);
      if ($request->has('estado')) {
        $cPlan->estado = $request->estado === true || $request->estado == 1;
      }
      $cPlan->suspender_desde = $request->suspender_desde;
      $cPlan->suspender_hasta = $request->suspender_hasta;
      $cPlan->save();
      return response()->success("Plan suspendido exitosamente.");

    }

    public function activar(Request $request,$id) {
      $cPlan = ClientePlan::find($id);
      if (!$cPlan) {
        return response()->json('El plan de cliente no existe',404);
      }
      $this->validate($request,[
        'fecha_activacion' => 'required|date',
        'fecha_expiracion' => 'required|date'
      ]);
      $cPlan->fecha_activacion = $request->fecha_activacion;
      $cPlan->fecha_expiracion = $request->fecha_expiracion;
      if ($request->habilitar) {
        $cPlan->suspender_desde = null;
        $cPlan->suspender_hasta = null;
      }
      if ($request->has('estado')) {
        $cPlan->estado = $request->estado === true || $request->estado == 1;
      }
      $cPlan->save();
      return response()->success("Plan activado exitosamente.");
    }
}
