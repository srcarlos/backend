<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Cuenta;
use App\Proveedor;

class CuentaController extends Controller
{
    public function store(Request $request,$proveedorId)
    {
        $proveedor = Proveedor::find($proveedorId);

        if (!$proveedor){
            return response()->json('El proveedor no existe',404);
        }

        $this->validate($request,[
            'tipo' => 'required',
            'banco' => 'required',
            'nro' => 'required|unique:cuentas,nro',
            'credito' => 'boolean',
            'dias' => 'required_if:credito,1',
            'monto_maximo' => 'required_if:credito,1',
        ]);

        Cuenta::create([
            'tipo' => $request->tipo,
            'banco' => $request->banco,
            'nro' => $request->nro,
            'credito' => $request->credito,
            'dias' => $request->dias,
            'monto_maximo' => $request->monto_maximo,
            'proveedor_id' => $proveedorId,
        ]);

        return response()->success('La cuenta ha sido creada exitosamente');
    }

    public function show($proveedorId, $cuentaId)
    {
        $cuenta = Cuenta::where(['proveedor_id'=>$proveedorId,'id'=>$cuentaId])->first();

        if (!$cuenta){
            return response()->json('La cuenta no existe',404);
        }

        return response()->success(compact('cuenta'));
    }

    public function update(Request $request,$proveedorId,$cuentaId)
    {
        $cuenta = Cuenta::where(['proveedor_id'=>$proveedorId,'id'=>$cuentaId])->first();

        if (!$cuenta){
            return response()->json('La cuenta no existe',404);
        }

        $this->validate($request,[
            'tipo' => 'required',
            'banco' => 'required',
            'nro' => 'required|unique:cuentas,nro,'.$cuenta->id,
            'credito' => 'boolean',
            'dias' => 'required_if:credito,1',
            'monto_maximo' => 'required_if:credito,1',
        ]);

        $cuenta->nombre = $request->nombre;
        $cuenta->apellido = $request->apellido;
        $cuenta->telefono = $request->telefono;
        $cuenta->telefono2 = $request->telefono2;
        $cuenta->save();

        return response()->success('La cuenta ha sido modificada exitosamente');
    }

    public function destroy($proveedorId, $cuentaId)
    {
        $cuenta = Cuenta::where(['proveedor_id'=>$proveedorId,'id'=>$cuentaId])->first();

        if (!$cuenta){
            return response()->json('La cuenta no existe',404);
        }

        $cuenta->delete();

        return response()->success('La cuenta ha sido eliminada exitosamente');
    }

    public function index($proveedor)
    {
        $cuenta = Cuenta::where('proveedor_id',$proveedor)->get();

        return response()->success(compact('cuenta'));
    }}
