<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Compania;
use Validator;
use Storage;

use App\Http\Requests;

class CompaniasController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "nombre" => "required|unique:companias,nombre",
            "razon_social" => "required|unique:companias,razon_social",
            "direccion" => "required",
            "sitio_web" => "required",
            "telf_particular" => "required|unique:companias,telf_particular",
            "telf_oficina" => "required|unique:companias,telf_oficina",
            //"logo" => "required",
            "rep_legal" => "required",
            "ruc" => "required|unique:companias,ruc",
            "ruc_rep_legal" => "required|unique:companias,ruc_rep_legal",
            "acceso_mod_ventas" => "boolean",
            "cantidad_platos" => "required_if:acceso_mod_ventas,0",
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()],422);
        }

        $company = Compania::create($request->all());

        if($request->hasFile('logo')){
            $company = Compania::find($company->id);
            $company->logo = $company->id.'.'.$request->file('logo')->extension();
            Storage::put('public/logos/'.$company->id.'.'.$request->file('logo')->extension(), file_get_contents($request->file('logo')->getRealPath()));
        }

        $company->save();

        return response()->success('La compañia ha sido creada exitosamente');
    }

    public function show($id)
    {
        $company = Compania::find($id);

        if (!$company){
            return response()->json('La compañia no existe',404);
        }

        return response()->success(compact('company'));
    }

    public function update(Request $request, $id)
    {
        //dd($request->all());

        $compania = Compania::find($id);

        if (!$compania){
            return response()->json('La compañia no existe',404);
        }

        $validator = Validator::make($request->all(),[
            "nombre" => "required|unique:companias,nombre,".$compania->id,
            "razon_social" => "required|unique:companias,razon_social,".$compania->id,
            "direccion" => "required",
            "sitio_web" => "required",
            //"telf_particular" => "required|unique:companias,telf_particular,".$compania->id,
            //"telf_oficina" => "required|unique:companias,telf_oficina,".$compania->id,
            //"logo" => "required",
            "rep_legal" => "required",
            "ruc" => "required|unique:companias,ruc,".$compania->id,
            "ruc_rep_legal" => "required|unique:companias,ruc_rep_legal,".$compania->id,
            "acceso_mod_ventas" => "boolean",
            "cantidad_platos" => "required_if:acceso_mod_ventas,0",
        ]);


        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()],422);
        }

        $compania->nombre = $request->nombre;
        $compania->razon_social = $request->razon_social;
        $compania->direccion = $request->direccion;
        $compania->sitio_web = $request->sitio_web;
        $compania->telf_particular = $request->telf_particular;
        $compania->telf_oficina = $request->telf_oficina;
        $compania->rep_legal = $request->rep_legal;
        $compania->ruc = $request->ruc;
        $compania->ruc_rep_legal = $request->ruc_rep_legal;
        $compania->acceso_mod_ventas = $request->acceso_mod_ventas;
        $compania->cantidad_platos = $request->cantidad_platos;

        //dd($request->logo);
        if ( $request->hasFile('logo') ) {
            if ( $compania->logo != null )
                Storage::delete('public/logos/'.$compania->logo);

            $logo = $compania->id.'.'.$request->file('logo')->extension();
            $compania->logo = $logo;
            //$compania->extension = $request->file('logo')->extension();
            $compania->logo = $compania->id.'.'.$request->file('logo')->extension();
            Storage::put('public/logos/'.$logo, file_get_contents($request->file('logo')->getRealPath()));
            //$compania->save();
        }

        $compania->save();

        return response()->success('La compañia ha sido actualizada exitosamente');
    }

    public function destroy($id)
    {
        $compania = Compania::find($id);

        if (!$compania){
            return response()->json('La compañia no existe',404);
        }

        if ( $compania->logo != null ){
            $logo = 'logos/'.$compania->logo;
            Storage::delete($logo);
        }

        $compania->delete();

        return response()->success('La compañia ha sido eliminada exitosamente');
    }

    public function index()
    {
        $company = Compania::all();

        return response()->success(compact('company'));
    }

    public function centrosByCompania($id)
    {
        $compania = Compania::find($id);
        $centro = $compania->centros;

        return response()->success(compact('centro'));
    }
}
