<?php

namespace App\Http\Controllers;

use App\IngredienteInsumo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Ingrediente;
use DB;

class IngredientesController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'nombre' => 'required|unique:ingredientes,nombre',
            'category_id' => 'required|numeric',
        ]);

        Ingrediente::create($request->all());

        return response()->success("Ingrediente creado exitosamente");
    }

    public function show($id)
    {
        $ingrediente = Ingrediente::find($id);

        if (!$ingrediente){
            return response()->json('El ingrediente no existe',404);
        }

        return response()->success(compact('ingrediente'));
    }

    public function update(Request $request,$id)
    {
        $ingrediente = Ingrediente::find($id);

        if (!$ingrediente){
            return response()->json('El ingrediente no existe',404);
        }

        $this->validate($request,[
            'nombre' => 'required|unique:ingredientes,nombre,'.$ingrediente->id,
            'category_id' => 'required|numeric',
        ]);

        $ingrediente->nombre = $request->nombre;
        $ingrediente->category_id = $request->category_id;
        $ingrediente->save();

        return response()->success('El ingrediente ha sido modificado exitosamente');
    }

    public function destroy($id)
    {
        $ingrediente = Ingrediente::find($id);

        if (!$ingrediente){
            return response()->json('El ingrediente no existe',404);
        }

        $ingrediente->delete();

        return response()->success('El ingrediente ha sido eliminado exitosamente');
    }

    public function index(Request $request)
    {
        $ingrediente = DB::table('ingredientes');
        $recordsTotal = Ingrediente::all()->count();
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
            'nombre' => 'nombre'
        ];

        if( !empty(trim($search)) ) {
            $ingrediente->where('id','like', '%'.$search.'%')
                ->orwhere('nombre','like', '%'.$search.'%');
        }
        if( !empty($orderCol) && isset($columns[$orderCol]) ) {
            $ingrediente->orderBy($columnNames[$columns[$orderCol]['data']], $orderDir);
        }

        $recordsFiltered = $ingrediente->count();

        if( intval($start) > 0 ) {
            $ingrediente->skip($start);
        }
        if( intval($length) > 0 ) {
            $ingrediente->take($length);
        }

        $ingrediente = $ingrediente->get();

        $response['ingrediente'] = $ingrediente;
        $response['draw'] = $draw;
        $response['recordsTotal'] = $recordsTotal;
        $response['recordsFiltered'] = $recordsFiltered;

        return response()->success($response);
    }

    public function removerInsumo($ingredienteId, $insumoId)
    {
        $insumo = IngredienteInsumo::where(['ingrediente_id' => $ingredienteId, 'insumo_id' => $insumoId])->first();

        if (!$insumo){
            return response()->json('El insumo no existe',404);
        }

        $insumo->delete();

        return response()->success('El insumo ha sido removido del ingrediente');
    }

    public function getInsumos($ingredienteId)
    {
        $insumos = [];
        // if (!$insumo){
        //     return response()->json('El Ingrediente no existe',404);
        // }
        $total = 0;

        $insumos = IngredienteInsumo::where(['ingrediente_id' => $ingredienteId])
            ->with([
                'insumos.unidad_produccion' => function ($q) { $q->select('id','nombre'); },
                "insumos"])
            ->get();

        //dd($insumos->toArray());

        foreach ($insumos as $insumo){
            $total += ($insumo->cantidad * $insumo->insumos->costo_unidad_produccion);
        }

        return response()->success(compact('insumos','total'));
    }

    public function addInsumo(Request $request)
    {
        $this->validate($request,[
            'insumo_id' => 'required|numeric',
            'ingrediente_id' => 'required|numeric',
            'cantidad' => 'required|numeric',

        ]);
        $exist = IngredienteInsumo::where('ingrediente_id',"=",$request->ingrediente_id)
            ->where( 'insumo_id',"=",  $request->insumo_id )
            ->first();
        if ($exist){
            return response()->error('Ya fue agregado', 401);
        }

//        IngredienteInsumo::create($request->all());
        IngredienteInsumo::create($request->all());


        return response()->success("Insumo Agregado");
    }
}
