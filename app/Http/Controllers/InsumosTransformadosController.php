<?php

namespace App\Http\Controllers;

use App\Existencia;
use Illuminate\Http\Request;
use App\InsumoTransformado;
use App\Insumo;
use App\BaseInsumoTransformado;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class InsumosTransformadosController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'nombre' => 'required|unique:insumos,nombre',
            'equivalencia' => 'required',
            'unidad_produccion' => 'required|numeric',
            //'unidad_compra' => 'required|numeric',
            'costo_unidad_produccion' => 'required|numeric',
            'insumos.*.insumo_id' => 'required|numeric',
            'insumos.*.cantidad' => 'required|numeric',
            'insumos.*.equivalencia' => 'required|numeric',
        ]);

        //dd($request->request);

        $id = "";

        DB::transaction(function ()use($request,&$id){

            $insumo_transformado = Insumo::create([
                'nombre' => $request->nombre,
                'tipo' => 1,
                'equivalencia' => $request->equivalencia,
                'unidad_produccion' => $request->unidad_produccion,
                'unidad_compra' => $request->unidad_compra,
                'costo_unidad_produccion' => $request->costo_unidad_produccion,
            ]);

            $id = $insumo_transformado->id;

            $insumo_transformado->insumosbase()->attach($request->insumos);
        });

        return response()->success(['success' => "Insumo transformado creado exitosamente",'id' => $id]);
    }

    public function show($id)
    {
        /*$insumo_transformado = Insumo::with([
            'insumosbase' => function($q) {
                $q->select('insumo_id','nombre');
            },
        ])->where('id',$id)->first();*/

        $insumotrans = Insumo::find($id);

        if (!$insumotrans){
            return response()->json('El insumo transformado no existe',404);
        }

        $insumosbase = BaseInsumoTransformado::where('insumotrans_id',$id)->with('insumo.unidad_compra')->get();

        $insumo_transformado['insumo_transformado'] = $insumotrans;
        $insumo_transformado['insumosbase'] = $insumosbase;

        return response()->success(compact('insumo_transformado'));
    }

    public function update(Request $request,$id)
    {
        $insumo_transformado = Insumo::find($id);

        if (!$insumo_transformado){
            return response()->json('El insumo transformado no existe',404);
        }

        $this->validate($request,[
            'nombre' => 'required|unique:insumos_transformados,nombre,'.$insumo_transformado->id,
            'equivalencia' => 'required',
            'unidad_produccion' => 'required|numeric',
            'costo_unidad_produccion' => 'required|numeric',
            'insumos.*.insumo_id' => 'required|numeric',
            'insumos.*.cantidad' => 'required|numeric',
        ]);

        DB::transaction(function () use($request,$insumo_transformado){

            $insumo_transformado->nombre = $request->nombre;
            $insumo_transformado->unidad_produccion = $request->unidad_produccion;
            $insumo_transformado->costo_unidad_produccion = $request->costo_unidad_produccion;
            $insumo_transformado->equivalencia = $request->equivalencia;

            if ($request->insumos){
                $insumo_transformado->insumosbase()->detach();
                $insumo_transformado->insumosbase()->attach($request->insumos);
            }

            $insumo_transformado->save();
        });

        return response()->success('El insumo transformado ha sido modificado exitosamente');
    }

    public function destroy($id)
    {
        $insumo_transformado = Insumo::find($id);

        if (!$insumo_transformado){
            return response()->json('El insumo transformado no existe',404);
        }

        $insumo_transformado->delete();

        return response()->success('El insumo transformado ha sido eliminado exitosamente');
    }

    public function index()
    {
        $insumo_transformado = Insumo::with([
            'unidad_produccion' => function ($q) { $q->select('id','nombre'); },
            'insumosbase',
        ])->where('tipo',1)->get();

        return response()->success(compact('insumo_transformado'));
    }

    public function vincularProveedor(Request $request,$id)
    {
        $insumoT = Insumo::find($id);

        if (!$insumoT){
            return response()->json('El insumo no existe',404);
        }

        $insumoT->proveedores()->attach($request->proveedor_id,[
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

    public function desvincularProveedor(Request $request,$id)
    {
        $insumoT = Insumo::find($id);

        if (!$insumoT){
            return response()->json('El insumo no existe',404);
        }

        $insumoT->proveedores()->detach($request->proveedor_id);

        return response()->success('Proveedor desvinculado exitosamente');
    }

    public function insumos_base($id)
    {
        $insumo = Insumo::find($id);

        $insumos_base = BaseInsumoTransformado::where('insumotrans_id',$id)->with(['insumotrans','insumo.unidad_compra'])->get();

        return response()->success(compact('insumos_base','insumo'));
    }

    public function whereNotIn($id)
    {
        $insumosbase = BaseInsumoTransformado::where('insumotrans_id',$id)->get(['insumo_id']);

        $ids = [];

        foreach ($insumosbase as $ib){
            $ids[] = (string)$ib['insumo_id'];
        }

        array_push($ids,$id);

        $wnot = Insumo::with('unidad_compra')->whereNotIn('id',$ids)->get();

        return response()->success(compact('wnot'));
    }

    public function storeIB(Request $request, $id)
    {
        $this->validate($request,[
            'cantidad' => 'required',
        ]);

        try{

            $insumoBase = BaseInsumoTransformado::create([
                'insumotrans_id' => $id,
                'cantidad' => $request->cantidad,
                'insumo_id' => $request->insumo_id,
                'equivalencia' => $request->equivalencia,
            ]);

            $insumoBase = Insumo::find($insumoBase->insumo_id);

//            dd($insumoBase->costo_unidad);

            $costoTotalIb = $request->cantidad * $insumoBase->costo_unidad;
//            dd($costoTotalIb);

            $insumo = Insumo::find($id);
            $insumo->costo_unidad_produccion = $insumo->costo_unidad_produccion + $costoTotalIb;
            $insumo->save();


            return response()->success('Insumo base registrado exitosamente');

        }catch (\Exception $e){
            return response()->error('Hubo un error al registrar el insumo base: '.$e->getMessage());
        }
    }

    public function indexIT()
    {
        $insumo_transformado = Insumo::where('tipo',1)->with([
            'unidad_produccion' => function ($q) { $q->select('id','nombre'); },
            'insumosbase',
        ])->get();

        //dd($insumo_transformado->toArray());

        return response()->success(compact('insumo_transformado'));
    }

    public function eliminarInsumoBase($insumoId,$insumo_baseId)
    {
        $insumobase = BaseInsumoTransformado::where(['insumotrans_id'=> $insumoId, 'insumo_id' => $insumo_baseId])->with('insumo')->first();

        if(!$insumobase){
            return response()->error('El insumo base no existe');
        }

        $valor = $insumobase->cantidad * $insumobase->insumo->costo_unidad;
        $insumobase->delete();

        $insumo = Insumo::find($insumoId);
        $insumo->costo_unidad_produccion -= $valor;
        $insumo->save();

        return response()->success('El insumo base ha sido eliminado');
    }

    public function validarInsumosBase(Request $request, $id)
    {
        //dd($request->all());

        $insumosBase = BaseInsumoTransformado::with('insumo.unidad_compra')->where('insumotrans_id',$id)->get();

        $_insumosBase = [];

        foreach ($insumosBase as $ib){
            $stock = Existencia::where(['bodega_id' => $request->bodega_id,'insumo_id' => $ib->insumo_id])->with(
                ['insumo','unidad','insumo.unidad_compra','bodega','seccion','posicion']
            )->get();

            $cantidad = ($ib['cantidad'] * $request->cantidad) / $ib->equivalencia;
            $existencias = [];
            if (count($stock) == 0){
                $ib->faltante = round($cantidad,2);
                $ib->stock = 0;
                $ib->existencia = 0;
                $ib->suficiente = false;
            } elseif(count($stock) > 0) {
                foreach ($stock as $st){
                    $st['faltante'] = $cantidad > $st['cantidad'] ? round(($cantidad - $st['cantidad']),4) : 0;
                    $st['existencia'] = $st['cantidad'];
                    $st['necesario'] = round($cantidad,2);
                    $st['suficiente'] = $cantidad > $st['cantidad'] ? false : true;
                    array_push($existencias,$st);
                }
                $ib->existencia = $existencias;
            }
            array_push($_insumosBase,$ib);
        }

        return response()->success(compact('_insumosBase'));
    }
}
