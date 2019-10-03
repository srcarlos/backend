<?php

namespace App\Http\Controllers;

use App\Contacto;
use App\Cuenta;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Proveedor;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
class ProveedorController extends Controller
{
    public function store(Request $request)
    {
        //dd($request->all());

        $this->validate($request,[
            'persona' => 'required',
            'ci' => 'required|unique:proveedores,ci',
            'nombre' => 'required',
            'apellido' => 'required',
            'direccion' => 'required',
            'telefono' => 'required|unique:proveedores,telefono',
            'identificacion' => 'required|unique:proveedores,identificacion',
            'correo' => 'required|email|unique:proveedores,correo',
            'activo' => 'required|boolean',
            'contactos.*.nombre' => "required",
            'contactos.*.apellido' => "required",
            'contactos.*.telefono' => "required|unique:contactos,telefono",
            'contactos.*.telefono2' => "required|unique:contactos,telefono2",
            'cuentas.*.tipo' => "required",
            'cuentas.*.banco' => "required",
            'cuentas.*.nro' => "required|unique:cuentas,nro",
            'credito' => "boolean",
            'dias' => "required_if:credito,true",
            'monto_maximo' => "required_if:credito,true",
          
        ]);

      

        try{
            DB::beginTransaction();

            $proveedor = Proveedor::create([
                "persona" => $request->persona,
                "ci" => $request->ci,
                "nombre" => $request->nombre,
                "apellido" => $request->apellido,
                "direccion" => $request->direccion,
                "telefono" => $request->telefono,
                "identificacion" => $request->identificacion,
                "correo" => $request->correo,
                "activo" => $request->activo,
                "credito" => $request->credito ? true : false,
                "dias" => $request->dias,
                "monto_maximo" => $request->monto_maximo,
            ]);

            if($request->contactos) {
                $proveedor->contactos()->createMany($request->contactos);
            }
            if($request->cuentas) {
                $proveedor->cuentas()->createMany($request->cuentas);
            }
            if($request->metodos) {
                $proveedor->metodos()->attach($request->metodos);
            }

            DB::commit();

        }catch(\Exception $e){
            DB::rollBack();

            return response()->error("Asegurese de que no existan datos repetidos".$e->getMessage());
        }

        return response()->success("Proveedor creado exitosamente");
    }

    public function show($id)
    {
        $proveedor = Proveedor::with('metodos')->where('id',$id)->first();

        //dd($proveedor->toArray());

        if (!$proveedor){
            return response()->json('El proveedor no existe',404);
        }

        return response()->success(compact('proveedor'));
    }

    public function update(Request $request,$id)
    {
        $proveedor = Proveedor::find($id);

        //dd($request->all());

        if (!$proveedor){
            return response()->json('El proveedor no existe',404);
        }

        $this->validate($request,[
            'persona' => 'required',
            'ci' => 'required|unique:proveedores,ci,'.$proveedor->id,
            'nombre' => 'required',
            'apellido' => 'required',
            'direccion' => 'required',
            'telefono' => 'required|unique:proveedores,telefono,'.$proveedor->id,
            'identificacion' => 'required|unique:proveedores,identificacion,'.$proveedor->id,
            'correo' => 'required|email|unique:proveedores,correo,'.$proveedor->id,
            'activo' => 'required|boolean',
            'credito' => "boolean",
            'dias' => "required_if:credito,true",
            'monto_maximo' => "required_if:credito,true",
        ]);

        try{
            DB::beginTransaction();

            $proveedor->persona = $request->persona;
            $proveedor->ci = $request->ci;
            $proveedor->nombre = $request->nombre;
            $proveedor->apellido = $request->apellido;
            $proveedor->direccion = $request->direccion;
            $proveedor->telefono = $request->telefono;
            $proveedor->identificacion = $request->identificacion;
            $proveedor->correo = $request->correo;
            $proveedor->activo = $request->activo;
            $proveedor->credito = $request->credito;
            $proveedor->dias = $request->dias;
            $proveedor->monto_maximo = $request->monto_maximo;


            if ($request->metodos){
                $proveedor->metodos()->sync($request->metodos);
            }

            if ($request->contactos){
                $proveedor->contactos()->delete();
                $proveedor->contactos()->createMany($request->contactos);
            }

            if ($request->cuentas){
                $proveedor->cuentas()->delete();
                $proveedor->cuentas()->createMany($request->cuentas);
            }

            $proveedor->save();

            DB::commit();

            return response()->success('El proveedor ha sido modificado exitosamente');

        }catch (\Exception $e){

            DB::rollBack();
            return response()->error('Ha ocurrido un error: '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::find($id);

        if (!$proveedor){
            return response()->json('El proveedor no existe',404);
        }

        $proveedor->delete();

        return response()->success('El proveedor ha sido eliminado exitosamente');
    }

    public function index(Request $request)
    {
        $proveedor = Proveedor::with('metodos');
        $recordsTotal = Proveedor::all()->count();
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
            'persona' => 'persona',
            'ci' => 'ci',
            'nombre' => 'nombre',
            'direccion' => 'direccion',
            'telefono' => 'telefono',
            'identificacion' => 'identificacion',
            'correo' => 'correo'
        ];

        if( !empty(trim($search)) ) {
            $proveedor->where('id','like', '%'.$search.'%')
                ->orwhere('persona','like', '%'.$search.'%')
                ->orwhere('ci','like', '%'.$search.'%')
                ->orwhere('nombre','like', '%'.$search.'%')
                ->orwhere('direccion','like', '%'.$search.'%')
                ->orwhere('telefono','like', '%'.$search.'%')
                ->orwhere('identificacion','like', '%'.$search.'%')
                ->orwhere('correo','like', '%'.$search.'%');
        }
        if( !empty($orderCol) && isset($columns[$orderCol]) ) {
            $proveedor->orderBy($columnNames[$columns[$orderCol]['data']], $orderDir);
        }

        $recordsFiltered = $proveedor->count();

        if( intval($start) > 0 ) {
            $proveedor->skip($start);
        }
        if( intval($length) > 0 ) {
            $proveedor->take($length);
        }

        $proveedor = $proveedor->get();
        $proveedor = $proveedor->toArray();

        $response['proveedor'] = $proveedor;
        $response['draw'] = $draw;
        $response['recordsTotal'] = $recordsTotal;
        $response['recordsFiltered'] = $recordsFiltered;

        return response()->success($response);
    }

    public function indexByEstatus()
    {
        $activos = Proveedor::where('activo', 1)->get();
        $inactivos = Proveedor::where('activo', 0)->get();

        return response()->success(compact(['activos','inactivos']));
    }

    public function search(Request $request)
    {
        $proveedor = Proveedor::where('persona','like',$request->data.'%')
            ->orWhere('persona','like',$request->data.'%')
            ->orWhere('nombre','like',$request->data.'%')
            ->orWhere('apellido','like',$request->data.'%')
            ->orWhere('direccion','like',$request->data.'%')
            ->orWhere('telefono','like',$request->data.'%')
            ->orWhere('identificacion','like',$request->data.'%')
            ->orWhere('correo','like',$request->data.'%')
            ->orWhere('activo','like',$request->data.'%')
            ->with('metodos')
            ->get();

        return response()->success(compact('proveedor'));
    }

    public function csv()
    {
        $proveedor = Proveedor::all();

        $csv = new \Laracsv\Export();

        $csv->build($proveedor,
            [   'id' => 'ID',
                'nombre' => 'Nombre',
                'direccion' => 'Dirección',
                'telefono' => 'Telefono',
                'identificacion' => 'Identificación',
                'correo' => 'Correo',
                'activo' => 'Activo',
            ]
        )->download('proveedores.csv');
    }

    public function pdf()
    {
        $proveedores = Proveedor::all();

        $pdf = PDF::loadView('PDF.proveedores_pdf',compact('proveedores'));

        return $pdf->download('proveedores.pdf');
    }


}
