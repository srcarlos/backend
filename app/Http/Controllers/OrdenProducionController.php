<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Planificacion;
use App\PlanificacionProduccion;
use App\PlanificacionProduccionPlato;
use App\PlanificacionProduccionInsumo;
use App\Ingrediente;
use App\PlatoIngrediente;
use App\Plato;
use Illuminate\Support\Facades\DB;

class OrdenProducionController extends Controller
{

    public function show($id)
    {
        $orden = PlanificacionProduccion::where("id", $id)
            ->with([
                "turno",
                "detalle.platos" => function ($q) {
                    $q->select('id', 'nombre');
                },
             //    "detalle.insumos.insumo" => function($q){ $q->select('id','nombre');},
                "detalle.insumos.insumo.proveedores" => function ($q) {
                    $q->select('*');
                },
               // "detalle.platos.ingredientes.ingrediente.insumos",
                "detalle.insumos.insumo.unidad_produccion" => function ($q) {
                    $q->select('id', 'nombre');
                },
            ])
            ->first();



        if (!$orden) {
            return response()->json('La planificacion no existe', 404);
        }
        // Obtengo el centro y la cocina que se ha planificado

        $planificacion = Planificacion::where("id", $orden->planificacion_id)->with(["centro","cocina"])->first();
        $orden["centro"] = $planificacion["centro"];
        $orden["cocina"] = $planificacion["cocina"];
        return response()->success(compact('orden'));
    }



    public function ordersByPlanificacionId($id)
    {

        $ordenes = PlanificacionProduccion::with([
            "turno" => function ($q) {
                $q->select('id', 'turno');
            }
        ])
            ->where("planificacion_id", "=", $id)->get();

        return response()->success(compact('ordenes'));
    }
   
      // insumos requeridos para orden de compra
    public function insumosOrdenCompra($id)
    {

        $orden = PlanificacionProduccion::where("id", $id)
            ->with([
                "detalle.insumos.insumo",
                "detalle.insumos.insumo.unidad_produccion" => function ($q) {
                    $q->select('id', 'nombre');
                },
                "detalle.insumos.insumo.unidad_compra" => function ($q) {
                    $q->select('id', 'nombre');
                },
            ])
            ->first();

        if (!$orden) {
            return response()->json('La orden no existe', 404);
        }

        $insumos = [];
        foreach ($orden["detalle"] as $key => $plato) {
            foreach ($plato["insumos"] as $k => $row) {
                $insumos[] = $row;

            }
        }

        // Se Suman Todos los insumos
        $insumosSum = [];
        foreach ($insumos as $k => $row) {
            $exits = false;
            foreach ($insumosSum as $ksuma) {
                if($ksuma->insumo_id==$row->insumo_id){
                    $ksuma->cantidad = $ksuma->cantidad + $row->cantidad ;
                    $exits =true;
                    break;
                }
             }
             if(!$exits){
                $row->id = null;
                $row->produccion_plato_id = null;
                $insumosSum[] = $row;  
             }
           
        }

        $ordenProduccion["orden_produccion_id"] = $orden["id"];
        $ordenProduccion["planificacion_id"] = $orden["planificacion_id"];
        $ordenProduccion["dia"] = $orden["dia"];
        $ordenProduccion["status"] = $orden["status"];
        $ordenProduccion["insumos"] = $insumosSum;

        return response()->success(compact('ordenProduccion'));
    }
    
    private function existeInsumo ($array, $value, $row){
        foreach ($array as $k) {
           if($k->insumo_id==$value){
               $k->cantidad = 100;
               return true;
               break;
           }
        }
        return false;
    }
    public function verificarEstatus(Request $request)
    {
        $ordenProduccion = PlanificacionProduccion::find($request->id['ordenId']);

        if (!$ordenProduccion) {
            return response()->json('La orden no existe', 404);
        }

        return response()->json(['op' => $ordenProduccion], 200);
    }

    public function ejecutar($id)
    {
        $ordenProduccion = PlanificacionProduccion::find($id);

        if (!$ordenProduccion) {
            return response()->json('La orden no existe', 404);
        }

        $ordenProduccion->status = 2;
        $ordenProduccion->save();

        return response()->success("Orden de producción en ejecución");
    }

    public function cerrar($id)
    {
        $ordenProduccion = PlanificacionProduccion::find($id);

        //dd($ordenProduccion->toArray());

        if (!$ordenProduccion) {
            return response()->json('La orden no existe', 404);
        }

        DB::transaction(function () use ($ordenProduccion) {

            $ordenProduccion->status = 5;
            $ordenProduccion->save();

            $planificacion = Planificacion::find($ordenProduccion->planificacion_id);

            $op = $planificacion->whereHas('produccion', function ($q) {
                $q->whereBetween('status', [0, 4]);
            })->first();

            //dd($op);

            if (!$op) {
                $planificacion->status = 6;
                $planificacion->save();
            }
        });

        return response()->success("Orden de producción cerrada");
    }

    public function anexar(Request $request)
    {


        try {

            $this->validate($request, [

                'orden_produccion_id' => 'required',
                'platos' => 'required|array',
                'platos.*.plato_id' => 'required',
                'platos.*.cantidad' => 'required',
            ]);

            $platos = $request->platos;
            $ordenProduccion = PlanificacionProduccion::find($request->orden_produccion_id);

            if (!$ordenProduccion) {
                return response()->json('La orden no existe', 404);
            }



            \DB::beginTransaction();

            foreach ($platos as $plato) {
                $platoPlan = PlanificacionProduccionPlato::create(['production_id' => $ordenProduccion->id, 'platos_id' => $plato["plato_id"], 'cantidad' => $plato["cantidad"]]);
                $platoIngredientes = PlatoIngrediente::where("plato_id", $plato["plato_id"])->with(["ingrediente.insumos"])->get();
            //1) Se registran los insumos de cada plato. Nota: se procesan de esta forma porque hay una relacion con Ingredientes.
                if (!count($platoIngredientes))
                    return response()->error($plato["plato_id"] . ", El plato no tiene ingredientes");
                $this->storeProduccionInsumo($platoIngredientes, $platoPlan);
            } //foreach  platos
            \DB::commit();
            return response()->success("Se han anexados platos");
        } catch (Exception $e) {

            \DB::rollback();
            return response()->error("No se pudo anexar platos");
        };// try/ catch

    }

    private function storeProduccionInsumo($platoIngredientes, $platoPlan)
    {
        // La Cantidad  Total de Insumo  = Candidad Ingredientes por Plato X Cantidad de Insumos Por Ingredientes.
        foreach ($platoIngredientes as $ingrediente) {
            $cantIngrediente = $ingrediente["cantidad"]; // Cantidad de Ingredientes por plato
            foreach ($ingrediente["ingrediente"]["insumos"] as $insumo) {
                $cantidadInsumo = $insumo["pivot"]["cantidad"]; // Cantidad de Insumos por Ingrediente.
                $insumoId = $insumo['id'];
                       //$platoId =  $plato["plato"]["id"]; //$ingredienteId =  $ingrediente['id'];
                $cantidadTotal = $cantidadInsumo * $cantIngrediente * $platoPlan->cantidad;
                PlanificacionProduccionInsumo::create(['produccion_plato_id' => $platoPlan["id"], 'insumo_id' => $insumoId, 'cantidad' => $cantidadTotal]);
                     //  echo "<br>cantidadInsumo  $cantidadInsumo, cantIngrediente $cantIngrediente";
                       //echo "<br>Turno  $turnoId, Plato $platoId, Ingrediente $ingredienteId,  Insumo:$insumoId, Cant Ing:".$cantIngrediente." x ".$cantidadInsumo;
            } // insumo

        } // foreach ingrediente


    }

}
