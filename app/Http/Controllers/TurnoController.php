<?php 

namespace App\Http\Controllers;

use App\Turno;
use Auth;

use Illuminate\Http\Request;
use Input;
use Validator;

class TurnoController extends Controller
{

    /**
    * Get all turnos.
    *
    * @return JSON
    */
    public function index()
    {
        $turnos = Turno::all();

        return response()->success(compact('turnos'));
    }


    /**
    * Create new turno.
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

        $turno = Turno::create($data);

        return response()->success(compact('turno'));
    }


    /**
    * Get Turno details referenced by id.
    *
    * @param int Turno ID
    *
    * @return JSON
    */
    public function show($id)
    {   

        $turno = Turno::find($id);

        return response()->success($turno);
    }

    /**
    * Update Turno data.
    *
    * @return JSON success message
    */
    public function update($id, Request $request)
    {   



        $turno = Turno::find($id);
        $data = $request->all();

        $validator = $this->validator($data,$id);

        if( $validator->fails() ){
            return response()->json(['errors' => $validator->errors()],422);
        }

        $turno->turno = $request->turno;
        $turno->desde = $request->desde;
        $turno->hasta = $request->hasta;
        $turno->cantidad = $request->cantidad;
        $turno->save();

        return response()->success(compact('turno'));
    }

    /**
    * Delete Turno Data.
    *
    * @return JSON success message
    */
    public function destroy($id)
    {
        $turno = Turno::find($id);
        
        if($turno){
            $turno->delete();
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
               'turno' => 'required',
               'desde' => 'required|date_format:H:i',
               'hasta' => 'required|date_format:H:i|after:desde',
               'cantidad' => 'required'

            ]);
        }

        return Validator::make($data, [
                'turno' => 'required',
                'desde' => 'required|date_format:H:i',
                'hasta' => 'required|date_format:H:i|after:desde',
                'cantidad' => 'required'
        ]);
    }

}
