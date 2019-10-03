<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\TipoMovimiento;

use DB;

class TipoMovimientosController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'accion' => 'required',
            'nombre' => 'required',
            'dato_extra' => 'required',
        ]);

        TipoMovimiento::create($request->all());

        return response()->success("Tipo de movimiento creado exitosamente");
    }

    public function show($id)
    {
        $tipo = TipoMovimiento::find($id);

        if (!$tipo){
            return response()->json('El tipo de movimiento no existe',404);
        }

        return response()->success(compact('tipo'));
    }

    public function update(Request $request,$id)
    {
        $tipo = TipoMovimiento::find($id);

        if (!$tipo){
            return response()->json('El tipo de movimiento no existe',404);
        }

        $this->validate($request,[
            'accion' => 'required',
            'nombre' => 'required',
            'dato_extra' => 'required',
        ]);

        $tipo->accion = $request->accion;
        $tipo->nombre = $request->nombre;
        $tipo->dato_extra = $request->dato_extra;
        $tipo->save();

        return response()->success('El tipo de movimiento ha sido modificado exitosamente');
    }

    public function destroy($id)
    {
        $tipo = TipoMovimiento::find($id);

        if (!$tipo){
            return response()->json('El tipo de movimiento no existe',404);
        }

        $tipo->delete();

        return response()->success('El tipo de movimiento ha sido eliminado exitosamente');
    }

    public function index(Request $request)
    {
        $tipo = DB::table('tipo_movimientos');
        $recordsTotal = TipoMovimiento::all()->count();
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
            'accion' => 'accion',
            'nombre' => 'nombre'
        ];

        if( !empty(trim($search)) ) {
            $tipo->where('id','like', '%'.$search.'%')
            ->orwhere('accion','like', '%'.$search.'%')
            ->orwhere('nombre','like', '%'.$search.'%');
        }
        if( !empty($orderCol) && isset($columns[$orderCol]) ) {
            $tipo->orderBy($columnNames[$columns[$orderCol]['data']], $orderDir);
        }

        $recordsFiltered = $tipo->count();

        if( intval($start) > 0 ) {
            $tipo->skip($start);
        }
        if( intval($length) > 0 ) {
            $tipo->take($length);
        }

        $tipo = $tipo->get();
        //$tipo = $tipo->toArray();

        $response['tipo'] = $tipo;
        $response['draw'] = $draw;
        $response['recordsTotal'] = $recordsTotal;
        $response['recordsFiltered'] = $recordsFiltered;

        return response()->success($response);
        //return response()->success(compact('tipo'));
    }

    public function search(Request $request)
    {
        $tipo = TipoMovimiento::where('accion','like',$request->data.'%')
            ->orWhere('nombre','like',$request->data.'%')
            ->orWhere('dato_extra','like',$request->data.'%')
            ->get();

        return response()->success(compact('tipo'));
    }
}
