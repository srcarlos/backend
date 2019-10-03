<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Cliente;
use App\Credito;
use App\Plan;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use DB;

class ClienteController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'nombres' => 'required',
            'apellidos' => 'required',
            'cedula' => 'required|unique:clientes,cedula',
            'celular' => 'required|unique:clientes,celular',
            'convencional' => 'required|unique:clientes,convencional',
            'email' => 'required|unique:clientes,email',
            'pais_id' => 'required',
            'provincia_id' => 'required',
            'ciudad_id' => 'required',
            'calle1' => 'required',
            'calle2' => 'required',
            'casa_nro' => 'required',
            'zona_id' => 'required',
            'referencias' => 'required',
        ]);

        $cliente = Cliente::create($request->all());

        if ($request->alergias) {
            $cliente->alergias()->attach($request->alergias);
        }
        $data = [];
        $data["data"] = "Cliente creado exitosamente";
        $data["errors"] = false;
        $data["cliente"] = $cliente;

          return response()->json($data ,200);
    }

    public function show($id)
    {
        $cliente = Cliente::where('id',$id)
            ->with('pais')
            ->with('provincia')
            ->with('ciudad')
            ->with('zona')
            ->with('planes')
            ->first();

        if (!$cliente){
            return response()->json('El cliente no existe',404);
        }
       
        return response()->success(compact('cliente'));
    }

    public function update(Request $request,$id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente){
            return response()->json('El cliente no existe',404);
        }

        $this->validate($request,[
            'nombres' => 'required',
            'apellidos' => 'required',
            'cedula' => 'required|unique:clientes,cedula,'.$cliente->id,
            'celular' => 'required|unique:clientes,celular,'.$cliente->id,
            'convencional' => 'required|unique:clientes,convencional,'.$cliente->id,
            'pais_id' => 'required',
            'provincia_id' => 'required',
            'ciudad_id' => 'required',
            'calle1' => 'required',
            'calle2' => 'required',
            'casa_nro' => 'required',
            'zona_id' => 'required',
            'referencias' => 'required',
        ]);

        $cliente->nombres = $request->nombres;
        $cliente->apellidos = $request->apellidos;
        $cliente->cedula = $request->cedula;
        $cliente->email = $request->email;
        $cliente->celular = $request->celular;
        $cliente->convencional = $request->convencional;
        $cliente->pais_id = $request->pais_id;
        $cliente->provincia_id = $request->provincia_id;
        $cliente->ciudad_id = $request->ciudad_id;
        $cliente->calle1 = $request->calle1;
        $cliente->calle2 = $request->calle2;
        $cliente->casa_nro = $request->casa_nro;
        $cliente->zona_id = $request->zona_id;
        $cliente->referencias = $request->referencias;
        $cliente->save();

        if ($request->alergias) {
            $cliente->alergias()->detach();
            $cliente->alergias()->attach($request->alergias);
        }

        return response()->success('El cliente ha sido modificado exitosamente');
    }

    public function destroy($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente){
            return response()->json('El cliente no existe',404);
        }

        $cliente->delete();

        return response()->success('El cliente ha sido eliminado exitosamente');
    }

    public function index(Request $request)
    {
        $cliente = Cliente::with('pais')
            ->with('provincia')
            ->with('ciudad')
            ->with('zona');

            $recordsTotal = Cliente::all()->count();
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
                'created_at' => 'created_at',
                'cedula' => 'cedula',
                'nombres' => 'nombres',
                'email' => 'email'
            ];

            if( !empty(trim($search)) ) {
                $cliente->where('id','like', '%'.$search.'%')
                    ->orwhere('created_at','like', '%'.$search.'%')
                    ->orwhere('cedula','like', '%'.$search.'%')
                    ->orwhere('nombres','like', '%'.$search.'%')
                    ->orwhere('email','like', '%'.$search.'%');
            }
            if( !empty($orderCol) && isset($columns[$orderCol]) ) {
               
                $cliente->orderBy($columnNames[$columns[$orderCol]['data']], $orderDir);
            }else{
                $cliente->orderBy($columnNames[$columns[$orderCol]['data']], $orderDir);
            }

            $recordsFiltered = $cliente->count();

            if( intval($start) > 0 ) {
                $cliente->skip($start);
            }
            if( intval($length) > 0 ) {
                $cliente->take($length);
            }

            $cliente = $cliente->get();
            $cliente = $cliente->toArray();

            $response['cliente'] = $cliente;
            $response['draw'] = $draw;
            $response['recordsTotal'] = $recordsTotal;
            $response['recordsFiltered'] = $recordsFiltered;

            return response()->success($response);
    }

    public function search(Request $request)
    {
        $search = trim($request->filter);

        $cliente = Cliente::where('nombres','like',"$search%")
            ->orWhere('apellidos','like',"%$search%")
            ->orWhere('cedula','like',"%$search%")
/*            ->orWhere('email','like',"%$search%")
            ->orWhere('celular','like',"%$search%")
            ->orWhere('convencional','like',"%$search%")
            ->orWhere('calle1','like',"%$search%")
            ->orWhere('calle2','like',"%$search%")
            ->orWhere('casa_nro','like',"%$search%")
            ->orWhere('zona_id','like',"%$search%")
            ->orWhere('referencias','like',"%$search%")*/
            ->with('pais')
            ->with('provincia')
            ->with('ciudad')
            ->with('zona')
            ->get();

       //dd($request->all());

        return response()->success(compact('cliente'));
    }

    /*public function creditos()
    {
        $plan = Cliente::with('creditos')->whereHas('credito',function ($query){
            $query->whereDate('fecha_expiracion','<=',Carbon::now()->toDateString());
        })->get();

        return response()->success(compact('plan'));
    }*/

    public function asignarPlan(Request $request,$id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente){
            return response()->json('El cliente no existe',404);
        }

        foreach ($request->planes as $plan){
            $cliente->planes()->attach($plan['plan_id'],
                [
                    'beneficiario' => $plan['beneficiario'],
                    'fecha_activacion' => $plan['fecha_activacion'],
                    'fecha_expiracion' => $plan['fecha_expiracion'],
                    'direccion_entrega' => $plan['direccion_entrega'],
                ]
            );
        }

        return response()->success('Plan asignado exitosamente');
    }

    public function removerPlan(Request $request,$id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente){
            return response()->json('El cliente no existe',404);
        }

        $cliente->planes()->detach($request->planes);

        return response()->success('Plan removido exitosamente');

    }

    public function creditos(Request $request)
    {
        //$cliente = Cliente::has('creditos')->with('facturas.credito')->get();


        $query = DB::table("creditos")
            ->join('clientes','creditos.cliente_id','=','clientes.id')
            //->join('cotizaciones','creditos.cotizacion_id','=','cotizaciones.id')
            ->select(
                'clientes.id as id',
                'creditos.factura_id as factura',
                'clientes.cedula as documento',
                'creditos.cotizacion_id as cotizacion',
                'clientes.nombres as nombres',
                'clientes.apellidos as apellidos',
                'clientes.email as email',
                'creditos.dias',
                'creditos.created_at as creacion',
                'creditos.fecha_expiracion'
            );
        if ($request->expired === '1' || $request->expired==="true") {
          $query->where('creditos.fecha_expiracion', '<', date('Y-m-d H:i:s'));
        }
        $cliente = $query->get();
        return response()->success(compact('cliente'));
    }

    public function creditosSearch(Request $request)
    {
        $cliente = Credito::where('dias','like',$request->data.'%')
            ->orWhere('fecha_expiracion','like',$request->data.'%')
            ->orWhereHas('cliente',function ($query) use($request){
                $query->where('nombres','like',$request->data.'%')
                    ->orWhere('apellidos','like',$request->data.'%')
                    ->orWhere('cedula','like',$request->data.'%')
                    ->orWhere('email','like',$request->data.'%');
            })
            ->with('cliente')
            ->get();

        return response()->success(compact('cliente'));
    }

    /*public function csv()
    {
        $cliente = Cliente::with('cocina')->get();

        $csv = new \Laracsv\Export();

        $csv->build($cliente,
            [
                'nombres' => 'Nombres',
                'apellidos' => 'Apellidos',
                'cedula' => 'Cedula',
                'tipo' => 'Tipo',
                'cocina.nombre' => 'Cocina',
            ]
        )->download('bodegas.csv');
    }*/

    /*public function pdf()
    {
        $bodegas = Bodega::all();

        $pdf = PDF::loadView('PDF.bodegas_pdf',compact('bodegas'));

        return $pdf->download('bodegas.pdf');
    }*/
}
