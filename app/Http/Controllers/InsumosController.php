<?php

namespace App\Http\Controllers;

use App\Cocina;
use App\Existencia;
use Illuminate\Http\Request;
use App\BaseInsumoTransformado;

use App\Http\Requests;
use App\Insumo;
use Illuminate\Support\Facades\DB;

class InsumosController extends Controller
{
    public function store(Request $request)
    {

        //dd($request->all());

        $this->validate($request,[
            'nombre' => 'required|unique:insumos,nombre',
            'tipo' => 'required',
            'unidad_compra' => 'required|numeric',
            'equivalencia' => 'required',
            'unidad_produccion' => 'required|numeric',
            'impuesto_id' => 'required|numeric',
            'marca' => 'required',
            'costo_unidad' => 'required',
            //'costo_unidad_produccion' => 'required',
        ]);

        try{
            DB::transaction(function ()use($request){

                $insumo = Insumo::create($request->all());

                if($request->tipo == 1){
                    foreach ($request->insumos_base as $base){
                        BaseInsumoTransformado::create([
                            'insumotrans_id' => $insumo->id,
                            'insumo_id' => $base['insumo_id'],
                            'cantidad' => $base['cantidad'],
                            'equivalencia' => $base['equivalencia'],
                        ]);
                    }
                }
            });
        }catch (\Exception $e){
            return response()->error("Se ha producido un error al intentar registrar el insumo: {$e->getMessage()}");
        }

        return response()->success("Insumo creado exitosamente");
    }

    public function show($id)
    {
        $insumo = Insumo::where('id',$id)->with('unidad_compra','unidad_produccion')->first();

        if (!$insumo){
            return response()->json('El insumo no existe',404);
        }

        return response()->success(compact('insumo'));
    }

    public function update(Request $request,$id)
    {
        $insumo = Insumo::find($id);

        if (!$insumo){
            return response()->json('El insumo no existe',404);
        }

        $this->validate($request,[
            'nombre' => 'required|unique:insumos,nombre,'.$insumo->id,
            'tipo' => 'required',
            'unidad_compra' => 'required|numeric',
            'equivalencia' => 'required',
            'unidad_produccion' => 'required|numeric',
            'impuesto_id' => 'required',
            'marca' => 'required',
            'costo_unidad' => 'required',
            'costo_unidad_produccion' => 'required',
        ]);

        $insumo->nombre = $request->nombre;
        $insumo->tipo = $request->tipo;
        $insumo->unidad_compra = $request->unidad_compra;
        $insumo->equivalencia = $request->equivalencia;
        $insumo->unidad_produccion = $request->unidad_produccion;
        $insumo->impuesto_id = $request->impuesto_id;
        $insumo->marca = $request->marca;
        $insumo->costo_unidad = $request->costo_unidad;
        $insumo->costo_unidad_produccion = $request->costo_unidad_produccion;
        $insumo->save();

        return response()->success('El insumo ha sido modificado exitosamente');
    }

    public function destroy($id)
    {
        $insumo = Insumo::find($id);

        if (!$insumo){
            return response()->json('El insumo no existe',404);
        }

        $insumo->delete();

        return response()->success('El insumo ha sido eliminado exitosamente');
    }

    public function index(Request $request)
    {
        $insumo = Insumo::with(["proveedores","unidad_compra"])->where('tipo',0);
        $recordsTotal = Insumo::all()->where('tipo',0)->count();
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
            'tipo' => 'tipo',
            'marca' => 'marca'
        ];

        if( !empty(trim($search)) ) {
            $insumo->where('id','like', '%'.$search.'%')
                ->orwhere('nombre','like', '%'.$search.'%')
                ->orwhere('tipo','like', '%'.$search.'%')
                ->orwhere('marca','like', '%'.$search.'%');
        }
        if( !empty($orderCol) && isset($columns[$orderCol]) ) {
            $insumo->orderBy($columnNames[$columns[$orderCol]['data']], $orderDir);
        }

        $recordsFiltered = $insumo->count();

        if( intval($start) > 0 ) {
            $insumo->skip($start);
        }
        if( intval($length) > 0 ) {
            $insumo->take($length);
        }

        $insumo = $insumo->get();
        $insumo = $insumo->toArray();

        $response['insumo'] = $insumo;
        $response['draw'] = $draw;
        $response['recordsTotal'] = $recordsTotal;
        $response['recordsFiltered'] = $recordsFiltered;

        return response()->success($response);
    }

    public function indexIT()
    {
        $insumo_transformado = Insumo::where('tipo',1)->with([
            'unidad_compra' => function ($q) { $q->select('id','nombre'); },
            'insumosbase',
        ])->get();

        //dd($insumo_transformado->toArray());

        return response()->success(compact('insumo_transformado'));
    }

    public function vincularProveedor(Request $request,$id)
    {
        $insumo = Insumo::find($id);

        if (!$insumo){
            return response()->json('El insumo no existe',404);
        }

        $insumo->proveedores()->attach($request->proveedor_id,[
            "precio" => $request->precio
        ]);

        return response()->success('Proveedor vinculado exitosamente');
    }

    public function proveedores($id)
    {
        $insumoT = Insumo::where('id',$id)->with([
            'unidad_produccion' => function ($q) { $q->select('id','nombre'); },
            'proveedores' => function ($q) { $q->select('proveedor_id','nombre'); },
        ])
            ->select('id','nombre','unidad_produccion')
            ->first();

        if (!$insumoT){
            return response()->json('El insumo no existe',404);
        }

        return response()->success(compact('insumoT'));
    }

    public function desvincularProveedor($insumoId,$proveedorId)
    {
        $insumoT = Insumo::find($insumoId);

        if (!$insumoT){
            return response()->json('El insumo no existe',404);
        }

        $insumoT->proveedores()->detach($proveedorId);

        return response()->success('Proveedor desvinculado exitosamente');
    }
}
