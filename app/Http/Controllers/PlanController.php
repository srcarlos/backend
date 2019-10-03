<?php

namespace App\Http\Controllers;

use Exception;

use App\Plan;
use App\TurnoPlan;
use Auth;

use Illuminate\Http\Request;
use Input;
use Validator;
use DB;

class PlanController extends Controller
{

    /**
    * Get all planes.
    *
    * @return JSON
    */
    public function index(Request $request)
    {
        $planes = DB::table('planes');
        $recordsTotal = Plan::all()->count();
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
            'nombre' => 'nombre',
            'precio' => 'precio',
            'almuerzo' => 'almuerzo',
            'cena' => 'cena',
            'snack' => 'snack'
        ];

        if( !empty(trim($search)) ) {
            $planes->where('id','like', '%'.$search.'%')
            ->orwhere('nombre','like', '%'.$search.'%')
            ->orwhere('precio','like', '%'.$search.'%')
            ->orwhere('almuerzo','like', '%'.$search.'%')
            ->orwhere('cena','like', '%'.$search.'%')
            ->orwhere('snack','like', '%'.$search.'%');
        }
        if( !empty($orderCol) && isset($columns[$orderCol]) ) {
            $planes->orderBy($columnNames[$columns[$orderCol]['data']], $orderDir);
        }

        $recordsFiltered = $planes->count();

        if( intval($start) > 0 ) {
            $planes->skip($start);
        }
        if( intval($length) > 0 ) {
            $planes->take($length);
        }

        $planes = $planes->get();

        foreach ($planes as $key => $value) {
          $value->turnos = TurnoPlan::with(["turno"])->where('plan_id','=',  $value->id)->get();
        }

        $response['planes'] = $planes;
        $response['draw'] = $draw;
        $response['recordsTotal'] = $recordsTotal;
        $response['recordsFiltered'] = $recordsFiltered;

        return response()->success($response);
    }


    /**
    * Create new plan.
    *
    * @return JSON
    */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = $this->validator($data,null);

        if( $validator->fails() ){
            return response()->json(['errors' => $validator->errors()],422);
        }
        // Validacion, en el cual no todos los items pueden ser cero
        // Al menos 1 item debe ser seteado
/*        if(!isset($data["cena"]))  $data["cena"]=0;
        if(!isset($data["snack"]))  $data["snack"]=0;
        if(!isset($data["almuerzo"]))  $data["almuerzo"]=0;

        if($data["almuerzo"]<=0 &&  $data["cena"]<=0 && $data["snack"]<=0 ){
             $error =  ['almuerzo' => ["Todos los items no pueden ser 0"]];
             return response()->json(['errors' => $error],422);

        }

        if($data["duracion"]<=0 ){

             $error =  ['duracion' => ["La duracion no puede ser 0"]];
             return response()->json(['errors' =>  $error ],422);

        }

         if($data["precio"]<=0 ){
             $error =  ['duracion' => ["El precio NO pueden ser Cero (0). "]];
             return response()->json(['errors' => $error],422);

        }*/
        $plan = [];
        try{
            DB::transaction(function () use($data){
                $plan = new Plan;
                 
                $plan->nombre    = $data["nombre"];
                $plan->precio    = $data["precio"];
                $plan->duracion  = $data["duracion"];
                $plan->save();
                
                $turnos = [];
                $validadCantidad = false; // todas las cantidades no pueden ser cero

                foreach ($data["turnos"] as $key => $value) {
                   $row = [];
                    $row["turno_id"]    = $value["id"];
                    $row["plan_id"]    = $plan->id; 
                    $row["cantidad"]   = $value["cantidad"];
                    $row["duracion"]   = $plan->duracion;
                    if($row["cantidad"]>0){
                       $validadCantidad = true;     
                    }
                    $turnos[] = $row;

                }
               // $plan->turnos()->delete();
                if( $validadCantidad){
                    $plan->turnos()->createMany($turnos);
                }else{
                     throw new Exception('Todas las cantidades no  pueden ser Cero ');
                }
                
            });
        }catch (\Exception $e){
            return response()->error('Error '.$e->getMessage());
        }


        return response()->success(compact('plan'));
    }


    /**
    * Get Plan details referenced by id.
    *
    * @param int Plan ID
    *
    * @return JSON
    */
    public function show($id)
    {

        $plan = Plan::with(["turnos.turno"])->find($id);

        return response()->success($plan);
    }



    /**
    * Get Plan details referenced by id.
    *
    * @param int Plan ID
    *
    * @return JSON
    */
    public function getTurnosByPlan($id)
    {

        $plan = Plan::with(["turnos"])->find($id);

        return response()->success($plan);
    }

    /**
    * Update Plan data.
    *
    * @return JSON success message
    */
    public function update($id, Request $request)
    {



        $plan = Plan::with(["turnos"])->find($id);
        $data = $request->all();

        $validator = $this->validator($data,$id);

        if( $validator->fails() ){
            return response()->json(['errors' => $validator->errors()],422);
        }

   /*      if(!isset($data["cena"]))  $data["cena"]=0;
         if(!isset($data["snack"]))  $data["snack"]=0;
         if(!isset($data["almuerzo"]))  $data["almuerzo"]=0;

        if($data["almuerzo"]<=0 &&  $data["cena"]<=0 && $data["snack"]<=0 ){
             $error =  ['almuerzo' => ["Todos los items no pueden ser 0"]];
             return response()->json(['errors' => $error],422);

        }

        if($data["duracion"]<=0 ){

             $error =  ['duracion' => ["La duracion no puede ser 0"]];
             return response()->json(['errors' =>  $error ],422);

        }

         if($data["precio"]<=0 ){
             $error =  ['duracion' => ["El precio NO pueden ser Cero (0). "]];
             return response()->json(['errors' => $error],422);

        }*/
    

          try{
            DB::transaction(function () use($data,  $plan){

                          
                $plan->nombre    = $data["nombre"];
                $plan->precio    = $data["precio"];
                $plan->duracion  = $data["duracion"];
                $plan->save();
          
                
            });

            }catch (\Exception $e){
            return response()->error('Error '.$e->getMessage());
        }

        return response()->success(compact('plan'));
    }

       /**
    * Update PlanTurno data.
    *
    * @return JSON success message
    */
    public function addTurno($id, Request $request)
    {


        $this->validate($request,[
            'turno_id' => 'required|exists:turnos,id',
            'cantidad' => 'required|integer',
        ]);

        $plan = Plan::find($id);


        if(!$plan){  
            return response()->json('No existe',401);
        }

        $data = $request->all();

         $exits = TurnoPlan::where('plan_id','=', $id)
        ->where('turno_id','=', $data["turno_id"])
        ->first();
        

         if($exits){  
          return response()->error('Ya Fue Agregado');
         }
 
          try{
                $turnoPlan = new TurnoPlan;
                $turnoPlan->plan_id   = $id;
                $turnoPlan->turno_id  = $data["turno_id"];
                $turnoPlan->cantidad  = $data["cantidad"];
                $turnoPlan->save();
            }catch (\Exception $e){
            return response()->error('Error '.$e->getMessage());
        }

        return response()->success(compact('plan'));
    }

    /**
    * deleteTurno  data.
    *
    * @return JSON success message
    */
    public function deleteTurno($id, Request $request)
    {


        $this->validate($request,[
            'turno_plan_id' => 'required',
        ]);

        $data = $request->all();

        $turnoPlan = TurnoPlan::find($data['turno_plan_id']);
      
       
         if($turnoPlan){
            $turnoPlan->delete();
            return response()->success('success');
         }

         return response()->error('No Existe ');

    }


    /**
    * deleteTurno  data.
    *
    * @return JSON success message
    */
    public function updateTurno($id, Request $request)
    {


        $this->validate($request,[
            'turno_plan_id' => 'required',
        ]);

        $data = $request->all();

        $turnoPlan = TurnoPlan::find($data['turno_plan_id']);
      
       
         if($turnoPlan){
            $turnoPlan->delete();
            return response()->success('success');
         }

         return response()->error('No Existe ');

    }

        /**
    * deleteTurno  data.
    *
    * @return JSON success message
    */
    public function getListTurno($id)
    {


         $turnoPlan = TurnoPlan::with(["turno"])->where('plan_id','=', $id)
        //->where('turno_id','=', $data["turno_id"])
        ->get();
      
       
         return response()->success(compact('turnoPlan'));

    }



    /**
    * Delete Plan Data.
    *
    * @return JSON success message
    */
    public function destroy($id)
    {
        $plan = Plan::find($id);

        if($plan){
            $plan->delete();
            return response()->success('success');
        }

         return response()->json('No existe',401);
    }


    /**
    *
    *
    *  Validador
    *
    *  ['nombre','precio','almuerzo','cena','snack','duracion']
    */
    protected function validator(array $data, $id = null){

        if ( $id ) {

            return Validator::make($data, [
               'nombre' => 'required|string',
               'precio' => 'required|numeric|min:0.001|between:0,99999999.99',
               'duracion' => 'required|integer|min:0',
               'turnos.*.turno_id' => 'required',
               'turnos.*.cantidad' => 'required'


            ]);
        }

        return Validator::make($data, [
               'nombre' => 'required|string',
               'precio' => 'required|numeric|min:0.001|between:0,99999999.99',
               'duracion' => 'required|integer|min:0',
               'turnos.*.id' => 'required',
               'turnos.*.cantidad' => 'required'
        ]);
    }

}
