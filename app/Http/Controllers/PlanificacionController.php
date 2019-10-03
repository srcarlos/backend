<?php

namespace App\Http\Controllers;

use App\OrdenHistorico;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Planificacion;
use App\PlanificacionProduccion;
use App\PlanificacionProduccionPlato;
use App\PlanificacionProduccionInsumo;
use App\Ingrediente;
use App\PlatoIngrediente;
use App\Orden;


class PlanificacionController extends Controller

{   /**
    1) Primero se crea el registro de Planificacion
    2) luego los registros de Produccion por dia y turnos. (Un turno y dia equivale a un registro de produccion)
    3) Platos ( Se crean los registros de platos por dia)
    4) Insumos ( Se crean los registros de insumos por platos)
    **/
    public function store(Request $request)
    {

        try{
           
        $this->validate($request,[
         
            'desde' => 'required',
            'hasta' => 'required',
            'centro_id' => 'required',
            'cocina_id' => 'required',  
            'dias' => 'required|array',
            'dias.*.total' => 'required',
            'dias.*.turnos' => 'required',
            'dias.*.turnos.*.*.plato' => 'required',
            'dias.*.turnos.*.*.cantidad' => 'required',
        ]);
        
         $mensaje_error = $this->validarDatosPlanificacion($request->dias);
         if($mensaje_error){
            return response()->error($mensaje_error);
         }
         \DB::beginTransaction();

         /**
         (listo) Por mejorar: validador antes de crear cualquier registro. ( requiere cambiar el json del request) 
          Por mejorar: reducir la cantidad de ciclos
          Por mejorar: guardar utilizando createMany (cambiar todo el json)

          version 2: Funcional
         */
        $planificacion = Planificacion::create($request->all()); 
        //1) Se itera por cada dia
        foreach($request->dias as $row) {
           //  $planificacion->produccion()->attach($planificacion->id,[ 'dia' =>  $row["dia"]]);
            //2) luego se itera por cada turno
           foreach($row["turnos"] as $turnoId => $turno) {
              // 3) Luego se crea el registro de cada orden de produccion por dia  y turno
              $produccion = PlanificacionProduccion::create(['planificacion_id' => $planificacion-> id, 'dia' => $row["dia"], 'turno_id' => $turnoId]);
              //4) Se registran los platos por cada turno
              foreach($turno as $plato) {
                 $platoPlan = PlanificacionProduccionPlato::create(['production_id' => $produccion -> id, 'platos_id' => $plato["plato"]["id"], 'cantidad' => $plato["cantidad"]]);
                 $platoIngredientes = PlatoIngrediente::where("plato_id", $plato["plato"]["id"]) -> with(["ingrediente.insumos"]) ->get();
                 //5) Se registran los insumos de cada plato. Nota: se procesan de esta forma porque hay una relacion con Ingredientes.
                 if(!count($platoIngredientes))
                    return response()->error($plato["plato"]["nombre"].", El plato no tiene ingredientes");
                 $this->storeProduccionInsumo($platoIngredientes,$platoPlan );
              } //foreach  platos
           } //foreach turno
        } ////foreach dia
        \DB::commit();
         return response()->success("Se ha creado la planificacion");
       } catch(Exception $e){

        \DB::rollback();
        return response()->error("No se pudo guardar la planificacion");
       };// try/ catch
      //  return response()->success("La Planificacion creada exitosamente");
    }

    private function validarDatosPlanificacion($dias)
    {
        //$planificacion = Planificacion::create($request->all()); 
        //1) Se itera por cada dia
        foreach($dias as $row) {
            //2) luego se itera por cada turno
           foreach($row["turnos"] as $turnoId => $turno) {
              // 3) Luego se crea el registro de cada orden de produccion por dia  y turno
             // $produccion = PlanificacionProduccion::create(['planificacion_id' => $planificacion-> id, 'dia' => $row["dia"], 'turno_id' => $turnoId]);
              //4) Se registran los platos por cada turno
              foreach($turno as $plato) {
                // $platoPlan = PlanificacionProduccionPlato::create(['production_id' => $produccion -> id, 'platos_id' => $plato["plato"]["id"], 'cantidad' => $plato["cantidad"]]);
                 $platoIngredientes = PlatoIngrediente::where("plato_id", $plato["plato"]["id"]) -> with(["ingrediente.insumos"]) ->get();
                 //5) Se registran los insumos de cada plato. Nota: se procesan de esta forma porque hay una relacion con Ingredientes.
                 if(!count($platoIngredientes)){
                    return "El plato '".$plato["plato"]["nombre"]."' no tiene ingredientes";
                    break;
                 }
                    foreach($platoIngredientes as $ingrediente) {  
                      if(!count($ingrediente["ingrediente"]["insumos"])){
                            return "El plato '".$plato["plato"]["nombre"]."' con el ingrediente  '".$ingrediente["ingrediente"]["nombre"]."' no tiene insumos";
                            break;
                        }                                           
                    } // foreach platoIngredientes
                } //foreach  platos
           } //foreach turno
        } ////foreach dia

        return 0;
    }
    public function show($id)
    {
        $planificacion = Planificacion::where("id",$id)
        ->with(["centro","produccion.detalle.platos","produccion.turno"])
        ->first();

        if (!$planificacion){
            return response()->json('La planificacion no existe',404);
        }

        return response()->success(compact('planificacion'));
    }

    public function update(Request $request,$id)
    {
        $planificacion = Planificacion::find($id);

        if (!$planificacion){
            return response()->json('La planificacion no existe',404);
        }

        $this->validate($request,[
            'desde' => 'required',
            'hasta' => 'required',
            'centro_id' => 'required',
        ]);

        $planificacion->desde = $request->desde;
        $planificacion->hasta = $request->hasta;
        $planificacion->centro_id = $request->centro_id;
        $planificacion->save();

        return response()->success('La planificacion  ha sido modificado exitosamente');
    }


    public function validarStock(Request $request)
    {

         $this->validate($request,[
            'status' => 'required',
            'id' => 'required',
        ]);

        $planificacion = Planificacion::find($request->id);

        if (!$planificacion){
            return response()->json('La planificacion no existe',404);
        }



        $planificacion->status = $request->status;
        $planificacion->save();

        return response()->success('La planificacion  ha sido modificado exitosamente');
    }

    public function destroy($id)
    {
        $planificacion = Planificacion::find($id);

        if (!$planificacion){
            return response()->json('La planificacion no existe',404);
        }

        $planificacion->delete();

        return response()->success('La planificacion ha sido eliminado exitosamente');
    }

    public function index(Request $request)
    {
        $planificaciones = Planificacion::with(["centro","produccion"]);
        $recordsTotal = Planificacion::all()->count();
        $settings = json_decode($request->settings, TRUE);
        $draw = $settings['draw'];
        $length = $settings['length'];
        $start = $settings['start'];
        $search = $settings['search']['value'];
        $orderCol = $settings['order'][0]['column'];
        $orderDir = $settings['order'][0]['dir'];
        $columns = $settings['columns'];

        $columnNames = [
            'id' => 'id',
            'desde' => 'desde',
            'hasta' => 'hasta',
            'status' => 'status'
        ];
        if( !empty(trim($search)) ) {
            $planificaciones->where('id','like', '%'.$search.'%')
                ->orwhere('desde','like', '%'.$search.'%')
                ->orwhere('hasta','like', '%'.$search.'%')
                ->orwhere('status','like', '%'.$search.'%');
                //->orwhere('email','like', '%'.$search.'%');
        }
        if( !empty($orderCol) && isset($columns[$orderCol]) ) {
            $planificaciones->orderBy($columnNames[$columns[$orderCol]['data']], $orderDir);
        }else{
            $planificaciones->orderBy($columnNames[$columns[$orderCol]['data']], $orderDir);

        }

        $recordsFiltered = $planificaciones->count();

        if( intval($start) > 0 ) {
            $planificaciones->skip($start);
        }
        if( intval($length) > 0 ) {
            $planificaciones->take($length);
        }

        $planificaciones = $planificaciones->get();
        $planificaciones = $planificaciones->toArray();

        $response['planificaciones'] = $planificaciones;
        $response['draw'] = $draw;
        $response['recordsTotal'] = $recordsTotal;
        $response['recordsFiltered'] = $recordsFiltered;

       return response()->success($response);
    }

    public function indexConTotalOrdenes()
    {
        $planificaciones = Planificacion::withTrashed()->withCount(['ordenes','ordenesHistorico'])->get();

        return response()->success(compact('planificaciones'));
    }

    public function ordenes($id)
    {
        $orden = OrdenHistorico::where('planificacion_id',$id)->with(['proveedor' => function($q){
            $q->select('id','nombre','apellido');
        }])->get();

        return response()->success(compact('orden'));
    }


    private function storeProduccionInsumo($platoIngredientes,$platoPlan){
        // La Cantidad  Total de Insumo  = Candidad Ingredientes por Plato X Cantidad de Insumos Por Ingredientes.
        foreach($platoIngredientes as $ingrediente) {
                    $cantIngrediente = $ingrediente["cantidad"]; // Cantidad de Ingredientes por plato
                    foreach($ingrediente["ingrediente"]["insumos"] as $insumo) {
                       $cantidadInsumo = $insumo["pivot"]["cantidad"]; // Cantidad de Insumos por Ingrediente.
                       $insumoId = $insumo['id'];
                       //$platoId =  $plato["plato"]["id"]; //$ingredienteId =  $ingrediente['id'];
                       $cantidadTotal = $cantidadInsumo * $cantIngrediente * $platoPlan->cantidad;
                       PlanificacionProduccionInsumo::create(['produccion_plato_id' => $platoPlan->id, 'insumo_id' => $insumoId, 'cantidad' => $cantidadTotal]);
                     //  echo "<br>cantidadInsumo  $cantidadInsumo, cantIngrediente $cantIngrediente";
                       //echo "<br>Turno  $turnoId, Plato $platoId, Ingrediente $ingredienteId,  Insumo:$insumoId, Cant Ing:".$cantIngrediente." x ".$cantidadInsumo;
                    } // insumo

        } // foreach ingrediente


    }
    public function verificarStatus(Request $request)
    {
        // Status de la planificacion 

           // case 0: "Borrador";
        // case 1:"Stock-Reserva";
        // case 2:"Ejecucion - Proceso de Compra";
        // case 3:"Ejecucion Parcial";
        // case 4:"Ejecucion Total"; 
        // case 5: "Completada";
        // case 6: "Cerrada";

        // Status de las ordenes de produccion
        // case 0:   "Sin Stock"
        // case 1:   "Pendiente"
        // case 2:   "En Ejecucion"
        // case 3:   "Ejecutada"
        // case 4:   "Completada"
        // case 5:   "Cerrada"
        
        $this->validate($request,[
            'id' => 'required',
        ]);
        
        $id = $request->id;
        $planificacion = Planificacion::find($id);

        if (!$planificacion){
            return response()->json('La planificacion no existe',404);
        }
       // Se revisan todas los ordenes de la planificacion
       $ordenes_produccion = PlanificacionProduccion::where("planificacion_id", $id)->get();
     
       $todoPendiente = true; 
       $ejecucionParcial = false;
       $sinStock = false;
       $todasSinStock = true;
       $cerrarPlanficacion = true;

      foreach($ordenes_produccion as $op) {
         if($op->status == 0){
            $sinStock = true;
         }
         if($op->status != 0){
            $todasSinStock = false;
         }
         if ($op->status != 1) {
            $todoPendiente = false;
         }
         if($op->status == 2){
          $ejecucionParcial = true;
        }

      
      }
     
      $response = array();
      if( $todoPendiente ){
        $response["codigo"]  = 1; // todo pendiente
        $response["mensaje"] = 'Todas las órdenes de producción tienen los insumos necesarios para ser ejecutadas. ';
        return response()->json(["data" =>$response],200);
      }else if($todasSinStock){
        $planificacion->status = 2; //  "Ejecucion - Proceso de Compra"
        $planificacion->save();  
        $response["codigo"]  = 3; // todas sin stock
        $response["mensaje"] = 'No existen insumos para ejecutar la planificación. ¿Desea continuar con su ejecución y generar Órdenes de Compra?';
        return response()->json(["data" =>$response],200);
      }else{
        $response["codigo"]  = 3; //  algunas sin stock
        $response["mensaje"] = 'La planificación seleccionada no cuenta con algunos de los insumos necesarios. ¿Desea continua su ejecucíon y generar las Órdenes de Compra?';
        return response()->json(["data" =>$response],200);
      }
    }
   
    public function verificarCerrar(Request $request)
    {
        // Status de la planificacion 

           // case 0: "Borrador";
        // case 1:"Stock-Reserva";
        // case 2:"Ejecucion - Proceso de Compra";
        // case 3:"Ejecucion Parcial";
        // case 4:"Ejecucion Total"; 
        // case 5: "Completada";
        // case 6: "Cerrada";

        // Status de las ordenes de produccion
        // case 0:   "Sin Stock"
        // case 1:   "Pendiente"
        // case 2:   "En Ejecucion"
        // case 3:   "Ejecutada"
        // case 4:   "Completada"
        // case 5:   "Cerrada"
        
        $this->validate($request,[
            'id' => 'required',
        ]);
        
        $id = $request->id;
        $planificacion = Planificacion::find($id);

        if (!$planificacion){
            return response()->json('La planificacion no existe',404);
        }
       // Se revisan todas los ordenes de la planificacion
       $ordenes_produccion = PlanificacionProduccion::where("planificacion_id", $id)->get();
     
       $cerrarPlanficacion = true;

      foreach($ordenes_produccion as $op) {
         if($op->status != 0 && $op->status != 1 && $op->status != 5){
            $cerrarPlanficacion = false;
         }
        
      }
     
      $response = array();
      if( $cerrarPlanficacion ){
        $response["codigo"]  = 1; // confimar cerraar
        $response["mensaje"] = '¿Desea cerrar la planificación?. ';
        return response()->json(["data" =>$response],200);
      }else {
        $planificacion->status = 2; //  
        $planificacion->save();  
        $response["codigo"]  = 2; // no puede cerrar la planificacion
        $response["mensaje"] = 'No es posibe cerrar la planificación seleccionada, debido a que alguna de sus ordenes de producción es en proceso de ejecución';
        return response()->json(["data" =>$response],200);
      }
    }
   
  
    public function cerrar(Request $request)
    {
       // Status de la planificacion 

        // case 0: "Borrador";
        // case 1: "Stock-Reserva";
        // case 2:"Ejecucion - Proceso de Compra";
        // case 3:"Ejecucion Parcial";
        // case 4:"Ejecucion Total"; 
        // case 5: "Completada";
        // case 6: "Cerrada";

        // Status de las ordenes de produccion
        // case 0:   "Sin Stock"
        // case 1:   "Pendiente"
        // case 2:   "En Ejecucion"
        // case 3:   "Ejecutada"
        // case 4:   "Completada"
        // case 5:   "Cerrada"
        $this->validate($request,[
            'id' => 'required',
        ]);
        
        $id = $request->id;
        $planificacion = Planificacion::find($id);

        if (!$planificacion){
            return response()->json('La planificacion no existe',404);
        }
       $ordenes_produccion = PlanificacionProduccion::where("planificacion_id", $id)->get();
       foreach($ordenes_produccion as $op) {
     
        $op->status = 4;
      
         $op->save();
        }

        $planificacion->status = 6; // "Cerrar";
        $planificacion->save();
        $response = array();
        $response["codigo"]  = 1; 
        $response["mensaje"] = 'Las órdenes pendientes ahora estan en ejecucíon ';
        return response()->json(["data" =>$response],200);
      
    }
    public function ejecutar(Request $request)
    {
       // Status de la planificacion 

        // case 0: "Borrador";
        // case 1:"Stock-Reserva";
        // case 2:"Ejecucion - Proceso de Compra";
        // case 3:"Ejecucion Parcial";
        // case 4:"Ejecucion Total"; 
        // case 5: "Completada";
        // case 6: "Cerrada";

        // Status de las ordenes de produccion
        // case 0:   "Sin Stock"
        // case 1:   "Pendiente"
        // case 2:   "En Ejecucion"
        // case 3:   "Ejecutada"
        // case 4:   "Completada"
        // case 5:   "Cerrada"
        $this->validate($request,[
            'id' => 'required',
        ]);
        
        $id = $request->id;
        $planificacion = Planificacion::find($id);

        if (!$planificacion){
            return response()->json('La planificacion no existe',404);
        }
       // Se revisan todas los ordenes de la planificacinn, las que estan pendiente pasan a ejecucion
       $ordenes_produccion = PlanificacionProduccion::where("planificacion_id", $id)->get();
       foreach($ordenes_produccion as $op) {
        if($op->status==1){
            $op->status = 2;
        }
         $op->save();
        }

        $planificacion->status = 4; // "Ejecucion Total";
        $planificacion->save();
        $response = array();
        $response["codigo"]  = 1; 
        $response["mensaje"] = 'Las órdenes pendientes ahora estan en ejecucíon ';
        return response()->json(["data" =>$response],200);
      
    }

    public function ejecutarParcial(Request $request)
    {
       // Status de la planificacion 

        // case 0: "Borrador";
        // case 1:"Stock-Reserva";
        // case 2:"Ejecucion - Proceso de Compra";
        // case 3:"Ejecucion Parcial";
        // case 4:"Ejecucion Total"; 
        // case 5: "Completada";
        // case 6: "Cerrada";

        // Status de las ordenes de produccion
        // case 0:   "Sin Stock"
        // case 1:   "Pendiente"
        // case 2:   "En Ejecucion"
        // case 3:   "Ejecutada"
        // case 4:   "Completada"
        // case 5:   "Cerrada"
        
        $this->validate($request,[
            'id' => 'required',
        ]);
        
        $id = $request->id;
        $planificacion = Planificacion::find($id);

        if (!$planificacion){
            return response()->json('La planificacion no existe',404);
        }
   
        $planificacion->status = 3; // "Ejecucion - Proceso de Parcial";
        $planificacion->save();
        $response = array();
        $response["codigo"]  = 1; 
        $response["mensaje"] = 'La planificacíon se ejecutara parcialmente';
        return response()->json(["data" =>$response],200);
      
    }

    public function ejecutarCompra(Request $request)
    {
       // Status de la planificacion 

        // case 0: "Borrador";
        // case 1:"Stock-Reserva";
        // case 2:"Ejecucion - Proceso de Compra";
        // case 3:"Ejecucion Parcial";
        // case 4:"Ejecucion Total"; 
        // case 5: "Completada";
        // case 6: "Cerrada";

        // Status de las ordenes de produccion
        // case 0:   "Sin Stock"
        // case 1:   "Pendiente"
        // case 2:   "En Ejecucion"
        // case 3:   "Ejecutada"
        // case 4:   "Completada"
        // case 5:   "Cerrada"
        
        $this->validate($request,[
            'id' => 'required',
        ]);
        
        $id = $request->id;
        $planificacion = Planificacion::find($id);

        if (!$planificacion){
            return response()->json('La planificacion no existe',404);
        }
   
        $planificacion->status = 2; // "Ejecucion - Proceso de Compra";
        $planificacion->save();
        $response = array();
        $response["codigo"]  = 1; 
        $response["mensaje"] = 'Cambio de status a Ejecucion - proceso de compra.';
        return response()->json(["data" =>$response],200);
      
    }
}
