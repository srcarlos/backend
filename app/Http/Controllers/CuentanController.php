<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Cuenta;

class CuentanController extends Controller
{
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
            'dias' => 'required_id:credito,1',
            'monto_maximo' => 'required_id:credito,1',
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
