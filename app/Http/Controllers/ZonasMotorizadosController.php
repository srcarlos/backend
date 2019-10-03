<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Zona;
use App\Motorizado;
use Barryvdh\DomPDF\Facade as PDF;
use App\ZonaMotorizado;


class ZonasMotorizadosController extends Controller
{
    public function index() {
        $zonas_motorizados = ZonaMotorizado::with(['motorizado', 'zona'])->get();
        return response()->success(compact('zonas_motorizados'));
    }

    public function show($id) {
        $zona_motorizado = ZonaMotorizado::with(['motorizado', 'zona'])->where('id', $id)->first();
        if (!$zona_motorizado){
            return response()->json('La zona/motorizado no existe.',404);
        }
        return response()->success(compact('zona_motorizado'));
    }

    public function store(Request $request) {
        $this->validate($request,[
            'zona_id' => 'required|unique:zona_motorizados,zona_id',
            'motorizado_id' => 'required|unique:zona_motorizados,motorizado_id'
        ]);
        $zona_motorizado = ZonaMotorizado::create($request->all());
        return response()->success("Registro creado exitosamente");
    }

    public function update(Request $request, $id) {
        $zona_motorizado = ZonaMotorizado::find($id);
        if (!$zona_motorizado){
            return response()->json('La zona/motorizado no existe.',404);
        }
        $this->validate($request,[
            'zona_id' => 'required|unique:zona_motorizados,zona_id,' . $zona_motorizado->id,
            'motorizado_id' => 'required|unique:zona_motorizados,motorizado_id,' . $zona_motorizado->id
        ]);
        $zona_motorizado->zona_id = $request->zona_id;
        $zona_motorizado->motorizado_id = $request->motorizado_id;
        $zona_motorizado->save();
        return response()->success('El registro ha sido actualizado satisfactoriamente.');
    }

    public function destroy(Request $request, $id) {
        $zona_motorizado = ZonaMotorizado::find($id);
        if (!$zona_motorizado){
            return response()->json('La zona/motorizado no existe.',404);
        }
        $zona_motorizado->delete();
        return response()->success('El registro ha sido eliminado satisfactoriamente.');
    }

    public function availableZonas($id=null) {
        $zonas = [];
        foreach(Zona::where('estado',1)->get() as $zona) {
            if (!$zona->motorizado || $zona->motorizado->id === intval($id)) {
                $zonas[] = ['id' => $zona->id, 'nombre' => $zona->nombre];
            }
        }
        return response()->success(compact('zonas'));
    }

    public function availableMotorizados($id=null) {
        $motorizados = [];
        foreach(Motorizado::where('estado', 1)->get() as $motorizado) {
            if (!$motorizado->zona || $motorizado->zona->id === intval($id)) {
                $motorizados[] = ['id' => $motorizado->id, 'nombre' => $motorizado->nombre];
            }
        }
        return response()->success(compact('motorizados'));
    }
}