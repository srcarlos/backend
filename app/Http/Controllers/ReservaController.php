<?php 

namespace App\Http\Controllers;

use App\ Existencia;
use App\ MovimientoReserva;
use App\ ReservaInsumo;
use App\ PlanificacionProduccion;
use App\ Planificacion;
use App\ Cocina;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller {
   public
   function store(Request $request, $id) {

      /*  $this->validate($request,[
            'orden_id' => 'required',
        ]);*/
      //$cocina_id = 1;

       // Parte I: Verificacion de Existencias

      //1) Obtengo el listado de insumos de la orden con sus cantidades
      $orden_produccion = PlanificacionProduccion::where("id", $id) 
       -> with(["detalle.insumos.insumo" ,
                "detalle.insumos.insumo.unidad_produccion" => function ($q) {
                  $q->select('id', 'nombre');
                  },
               "detalle.insumos.insumo.unidad_compra" => function ($q) {
                 $q->select('id', 'nombre');
                }
         ]) 
         -> first();

      if (!$orden_produccion) {
         return response()->json('La orden no existe', 404);
      }

      if ($orden_produccion->status == 1) {
         return response()->success(" Ya ha reservado los insumos para esta orden");
      }
      $insumos = []; // almaceno cada insumo en un array
      $_insumos = []; // almaceno solo los $id de los insumo
      foreach($orden_produccion["detalle"] as $key => $plato) {
         foreach($plato["insumos"] as $k => $row) {
            $_insumos[] = $row["insumo_id"];
            $insumos[] = $row;
         }
      }

      //2) Busco en las bodegas del centro de produccion si hay insumos disponibles
      //2.1) Primero obtengo el id del centro de produccion desde la planificacion
      $planificacion = Planificacion::where("id", $orden_produccion->planificacion_id)->with(["centro"])->first();

      //bodegas del centro de produccion
      $cocinas = Cocina::where('centro_produccion_id', $planificacion->centro->id)-> with('bodegas')->get();
      $bodegas = [];
      //almaceno los id de las bodegas en un array
      foreach($cocinas as $cocina) {
         foreach($cocina->bodegas as $b) {
            $bodegas[] = $b->id; // estas son las bodegas que pertenecen al centro de produccion
         }
      }
      //2.2 Se obtiene la existencia  segun los id en el array $_insumos
      // solo se busca en las bodegas del centro de produccion  
      $stock = Existencia::where('cantidad', '>', 0)
         -> whereIn('insumo_id', $_insumos)
          ->whereIn('bodega_id',$bodegas)
         -> get();

  
      // Se crea un array con el detalle de la reserva, en este mismo proceso se verifica si se cuenta con la cantidad total del insumos

      $detalleReserva = array ();
      foreach($insumos as $i) {
         $i["reservar"] = 0;
         //s $i["faltante"] = 0;
         foreach($stock as $s) {
           if(!$s["reservaTotal"] && !$i["reservadoTotal"])
            if ($i["insumo_id"] == $s["insumo_id"]) {

               if ($s["cantidad"]>0 && $i["cantidad"]>0 && $s["cantidad"] >= $i["cantidad"] ) {
                   
                   $i["reservar"] = $i["cantidad"];
                   $s["reservar"] = $i["cantidad"]; // indico la cantidad a reservar

                   $s["cantidad"] = $s["cantidad"] - $i["cantidad"];
                   $i["cantidad"] = 0;

                   $i["reservadoTotal"] = true; // indico que ya reserve el insumo totalmente
                  if($s["cantidad"]>0){
                    $s["reservaTotal"] = false;
                  }else {
                    $s["reservaTotal"] = true;  // ya no hay mas para reservar en la existencia
                  }
                  
                   array_push($detalleReserva,$s);
               } else {

                  // Solo reserva lo que hay en esa posicion de la existencia y pasa a las siguientes.
                  // Se reserva solo una parte.
                  $i["reservado"] = false;
                  if ($s["cantidad"]>0 && ($i["cantidad"] - $s["cantidad"]) >=0){
                    $i["reservar"] = $s["cantidad"];
                    $s["reservar"] = $s["cantidad"];  // indico la cantidad a reservar
                    $i["cantidad"] = $i["cantidad"] - $s["cantidad"]; // descuenta la cantidad necesaria y voy al siguiente ciclo stock.

                    $i["reservaParcial"] = true;
                    $s["reservaTotal"] = true; // se supone que ya no queda nada en el stock
                   array_push($detalleReserva,$s);
                  }else {
                    $s["reservado"] = false;
                  }
                  //$s["cantidad"] = $s["cantidad"] - $i["cantidad"];

               }
            }
         }
      }

      //3) Si no hay insumos reservados disponibles se indica  el error. (generar la orden de compra para esa orden.)
      foreach($insumos as $i) {
        if(!$i["reservadoTotal"]){
          return response()->error($i["insumo"]["nombre"].", No hay Insumos suficientes para reservar");
        }
      }

      $data = [];
      $data['cocina_id'] = $planificacion->cocina_id;
      $data['centro_id'] = $planificacion->centro->id;
      $data['production_id'] = $id;
      $data['fecha'] = date("Y-m-d H:i:s");

      //  En este punto Inicia la transaccion para descontar de la existencia y crear el movimiento de reserva
      // Parte II: Creacion de movimiento y descuento de la existencia.

      try{
      \DB::beginTransaction();
      $moviento = MovimientoReserva::create($data);
      //4) Descontar de la tabla existencia lo reservado
      foreach($detalleReserva as $detalle) {
        
         $existencia = Existencia::where('id', '=', $detalle->id)->first();
         if(!$existencia){
          return response()->error(" Fue eliminado un insumo de la existencia al momento de reservar.");
         }
         if($existencia->cantidad <= 0){
          return response()->error("Insumo sin existencia al momento de reservar");
         }

         if( ($existencia->cantidad - $detalle->reserva) <= 0){
          return response()->error("Insumo sin existencia al momento de reservar.");
         }

         $existencia->cantidad = $existencia->cantidad - $detalle->reservar;// descuenta de la existencia
         $reservaInsumo["insumo_id"] = $detalle->insumo_id;
         $reservaInsumo["bodega_id"] = $detalle->bodega_id;
         $reservaInsumo["seccion_id"] = $detalle->seccion_id;
         $reservaInsumo["posicion_id"] = $detalle->posicion_id;
         $reservaInsumo["cantidad"] =  $detalle->reservar;;
         $reservaInsumo["unidad"] = $detalle->unidad;
         $reservaInsumo["movimientoreserva_id"] = $moviento->id;
         ReservaInsumo::create($reservaInsumo);
         $existencia->save();

      }
      //5) Cambiar el status de la orden de produccion
      $orden_produccion->status = 1; //1 Pendente
      $orden_produccion->save();

      // 6) Cambio el estatus de la planicacion si todas las ordenes ya se han reservado $orden_produccion->planificacion_id
      $ordenes_produccion = PlanificacionProduccion::where("planificacion_id", $orden_produccion->planificacion_id)->get();
      $todo_reservado = true;
      foreach($ordenes_produccion as $op) {
         if ($op->status = 0) { // alguna orden sin stock
            $todo_reservado = false;
         }  
      }
    
      if ($todo_reservado) {
        $planificacion->status = 1; // Stock Reserva
        $planificacion->save();
       }

      \DB::commit();
      return response()->success("Se ha creado la reserva de stock");
    } catch(Exception $e){

     \DB::rollback();
     return response()->error("No se pudo realizar la reserva. ");
    };// try/ catch
      
   }

   
   


}
