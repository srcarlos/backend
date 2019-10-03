<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Factura;
use App\FacturaPlan;
use App\PlanificacionProduccionPlato;
use App\CentroProduccion;
use App\Planificacion;
use App\Cliente;
use App\Compania;
use App\Insumo;
use App\Plan;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;


class DashboardCompaniaController extends Controller
{

  public function index(Request $request)
  {  
       // $this->validate($request,[
          //  'date' => 'required',
          //  'company_id' => 'required|numeric',
       // ]);

    $total_ventas = Factura::where("total", ">=", "0")->sum('total');

    return response()->success(compact('total_ventas'));
  }
  public function costos(Request $request)
  {  
    // Plato | Costo Unitario | Cantidad  Platos | Costo Total
       // $this->validate($request,[
          //  'date' => 'required',
          //  'company_id' => 'required|numeric',
       // ]);

      $this->validate($request,[
        'cocina_id' => 'required',
        'centro_id' => 'required',
        'desde' => 'required|date',
        'hasta' => 'required|date'
      ]);
      

    $cocina_id = $request->cocina_id;
    $centro_id = $request->centro_id;

    $reporte = Planificacion::with([
      'centro' => function ($q) {
        $q->select("id", "nombre");
      },
      'centro.compania' => function ($q) {
        $q->select("id", "nombre");
      },
      'produccion.detalle.platos',
      'produccion.detalle.platos.ingredientes',
      'produccion.detalle.insumos',
      'produccion.detalle.insumos.insumo',

    ])
    ->where("cocina_id","=",$cocina_id)
    ->where("centro_id","=",$centro_id)
    ->whereDate('desde','>=',  Carbon::parse($request->desde)->format('Y-m-d')  )
    ->whereDate('hasta','<=',  Carbon::parse($request->hasta)->format('Y-m-d')  )

     //->where("id", "=", 169) 
   

     ->get();

    if (count($reporte) == 0){
        return response()->error('No existen datos para reporte');
    }

    //$clientes = Planificacion::get()->count();
    return response()->success(compact('reporte'));
  }

  public function dashboard(Request $request, $id)
  {
    $dashboard = [];

    $dashboard["total_clientes"] = Cliente::get()->count();
    $dashboard["total_insumos"] = Insumo::get()->count();

    $planificaciones = [];
    $ganancias = 0;
  
    $planificaciones = $this->getPlanificaciones($id);
     
    $total_ventas =  $this->getTotalVentas($id);//Factura::where("total", ">=", "0")->sum('total');

    $dashboard["total_planificaciones"]   = count($planificaciones);
    $dashboard["planificaciones"]         = $planificaciones;
    $dashboard["costos"]                  = $this->getCostos($planificaciones);
    $dashboard["ganancias"]               = $total_ventas - $dashboard["costos"] ;
    $dashboard["total_ventas"]               = $total_ventas ;

    $dashboard["planes_registrados"]       =  Plan::get()->count(); ;
    $dashboard["planes_vendidos"]         = $this->getPlanesMasVendidos($id);
    //$dashboard["planes_vencidos"]         = $this->getPlanesMasVendidos();

    $dashboard["platos_preparado"]        =  $this->getPlatosMasPreparados();
    $dashboard["zona_mayor_entrega"]      = $ganancias;

    return response()->success($dashboard);
  }
  private function getPlanificaciones ($id){
 
    return DB::table('planificaciones')
    ->join('centros_produccion', 'centros_produccion.id', '=', 'planificaciones.centro_id')
    ->join('companias', 'companias.id', '=', 'centros_produccion.compania_id')
    ->where('compania_id', '=', $id)
    ->select('planificaciones.id','planificaciones.status' ,'planificaciones.costo')
    ->get();
  }

  private function getTotalVentas ($id){

    return DB::table('facturas')
    ->join('cotizaciones', 'cotizaciones.id', '=', 'facturas.cotizacion_id')
    ->where('cotizaciones.compania_id', '=', $id)
    ->where("facturas.total", ">=", "0")
    ->sum('facturas.total');
  }


  private function getCostos ($planificaciones){
    $costos = 0.0;
    foreach ($planificaciones as $key ) {
       $costos = $costos + $key->costo ;
    };

    return $costos;
  }

  private function getPlanesMasVendidos ($id){
     $plan = DB::table('factura_plan')

    ->join('facturas', 'facturas.id', '=', 'factura_plan.factura_id')  
    ->join('planes', 'planes.id', '=', 'factura_plan.plan_id')  
    ->join('cotizaciones', 'cotizaciones.id', '=', 'facturas.cotizacion_id')
    ->where('cotizaciones.compania_id', '=', $id)
    ->select('factura_plan.plan_id','planes.nombre', DB::raw("count(planes.id) as total"))
    ->groupBy('factura_plan.plan_id')
    ->orderBy('total', 'desc')
    ->limit(1)
    ->first();
    if($plan){
      return  $plan->nombre;
    }else{
      return  "Sin Ventas";

    }
  }

  private function getPlatosMasPreparados (){
    $plato = DB::table('planificacion_produccion_platos')
   ->join('platos', 'platos.id', '=', 'planificacion_produccion_platos.platos_id')
   ->select('planificacion_produccion_platos.platos_id','platos.nombre', DB::raw("count(platos.id) as total"))
   ->groupBy('planificacion_produccion_platos.platos_id')
   ->orderBy('total', 'desc')
   ->limit(1)
   ->first();
   if($plato){
     return  $plato->nombre;
   }else{
     return  "Sin O.P.";

   }
 }
}