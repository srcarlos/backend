<?php

namespace App\Http\Controllers;

use App\Proveedor;
use Illuminate\Http\Request;
use App\Contacto;

use App\Http\Requests;

class ContactoController extends Controller
{
    public function store(Request $request,$proveedorId)
    {
        $proveedor = Proveedor::find($proveedorId);

        if (!$proveedor){
            return response()->json('El proveedor no existe',404);
        }

        $this->validate($request,[
            'nombre' => 'required',
            'apellido' => 'required',
            'telefono' => 'required|unique:contactos,telefono',
            'telefono2' => 'required|unique:contactos,telefono2',
        ]);

        Contacto::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'telefono2' => $request->telefono2,
            'proveedor_id' => $proveedorId,
        ]);

        return response()->success("Contacto creado exitosamente");
    }

    public function show($proveedorId,$contactoId)
    {
        $contacto = Contacto::where(['proveedor_id'=>$proveedorId,'id'=>$contactoId])->first();

        if (!$contacto){
            return response()->json('El contacto no existe',404);
        }

        return response()->success(compact('contacto'));
    }

    public function update(Request $request,$proveedorId,$contactoId)
    {
        $contacto = Contacto::where(['proveedor_id'=>$proveedorId,'id'=>$contactoId])->first();

        if (!$contacto){
            return response()->json('El contacto no existe',404);
        }

        $this->validate($request,[
            'nombre' => 'required',
            'apellido' => 'required',
            'telefono' => 'required|unique:contactos,telefono,'.$contacto->id,
            'telefono2' => 'required|unique:contactos,telefono2,'.$contacto->id,
        ]);

        $contacto->nombre = $request->nombre;
        $contacto->apellido = $request->apellido;
        $contacto->telefono = $request->telefono;
        $contacto->telefono2 = $request->telefono2;
        $contacto->save();

        return response()->success('El contacto ha sido modificado exitosamente');
    }

    public function destroy($proveedorId,$contactoId)
    {
        $contacto = Contacto::where(['proveedor_id'=>$proveedorId,'id'=>$contactoId])->first();

        if (!$contacto){
            return response()->json('El contacto no existe',404);
        }

        $contacto->delete();

        return response()->success('El contacto ha sido eliminado exitosamente');
    }

    public function index($proveedor)
    {
        $contacto = Contacto::where('proveedor_id',$proveedor)->get();

        return response()->success(compact('contacto'));
    }
}
