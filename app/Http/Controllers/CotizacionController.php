<?php

namespace App\Http\Controllers;

use App\Factura;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Cotizacion;
use App\CotizacionPlan;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;
use Mail;
use Carbon\Carbon;



class CotizacionController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'compania_id' => 'required',
            'prospecto' => 'required',
            'pais_id' => 'required',
            'provincia_id' => 'required',
            'ciudad_id' => 'required',
            'zona_id' => 'required',
            'calle1' => 'required',
            'casa_nro' => 'required',

            //'fecha_activacion' => 'required',
            //'fecha_expiracion' => 'required',
            'descuento_porcentaje' => 'required',
            'descuento_total' => 'required',
            'sub_total' => 'required',
            'total' => 'required',

            'planes.*.plan_id' => "required",
            'planes.*.cantidad' => "required",

        ]);

        $cotizacion = Cotizacion::create([
            "compania_id" => $request->compania_id,
            "pais_id" => $request->pais_id,
            "provincia_id" => $request->provincia_id,
            "ciudad_id" => $request->ciudad_id,
            "zona_id" => $request->zona_id,
            "calle1" => $request->calle1,
            "calle2" => $request->calle2,
            "casa_nro" => $request->casa_nro,
            "referencias" => $request->referencias,
            "prospecto" => $request->prospecto,
            "descuento_porcentaje" => $request->descuento_porcentaje,
            "descuento_total" => $request->descuento_total,
            "sub_total" => $request->sub_total,
            "total" => $request->total,
        ]);

        if($request->planes) {
            $cotizacion->planes()->createMany($request->planes);
        }else {
            return response()->error("Cotizacion no creada");
        }

        $this->mailSend($cotizacion->id);

        return response()->success("Cotizacion creada exitosamente");
    }

    public function show($id)
    {
        $cotizacion = Cotizacion::where("id","=",$id)
            ->with('pospecto')
            ->with('planes')
            ->with('planes.plan.turnos.turno')
            ->with('pospecto.pais')
            ->with('pospecto.provincia')
            ->with('pospecto.ciudad')
            ->with('pospecto.zona')
            ->first();

        $factura = Factura::where('cotizacion_id',$id)
            ->with('credito')
            ->with('cheque')
            ->with('deposito')
            ->with('transferencia')
            ->with('tarjeta_credito')
            ->first();

        $cotizacion->factura = $factura;

        if (!$cotizacion){
            return response()->json('La cotizacion no existe',404);
        }

        $comprobantes = Storage::files('public/comprobantes/C'.$cotizacion->id);

        $c = [];

        if (count($comprobantes) > 0){
            foreach ($comprobantes as $comprobante){
                $arr = explode('/',$comprobante);
                array_push($c,$arr[3]);
            }

            $comprobantes = $c;
        }

        return response()->success(compact('cotizacion','comprobantes'));
    }

    public function update(Request $request,$id)
    {
        $cotizacion = Cotizacion::find($id);

        if (!$cotizacion){
            return response()->json('La cotizacion no existe',404);
        }

        $this->validate($request,[
            'prospecto' => 'required',
            'descuento_porcentaje' => 'required',
            'descuento_total' => 'required',
            'sub_total' => 'required',
            'total' => 'required',
            'planes.*.plan_id' => "required",
            'planes.*.cantidad' => "required",
        ]);



        $cotizacion->prospecto = $request->prospecto;
        $cotizacion->descuento_porcentaje = $request->descuento_porcentaje;
        $cotizacion->descuento_total = $request->descuento_total;
        $cotizacion->sub_total = $request->sub_total;

        $cotizacion->save();


        if($request->planes) {
            $cotizacion->planes()->createMany($request->planes);
        }else {
            return response()->error("Cotizacion no actualizada creada");
        }


        return response()->success('La cotizacion ha sido modificada exitosamente');
    }


    public function pay(Request $request,$id)
    {

    }

    public function destroy($id)
    {
        $cotizacion = Cotizacion::find($id);

        if (!$cotizacion){
            return response()->json('La cotizacion no existe',404);
        }

        $cotizacion->delete();

        return response()->success('La cotizacion ha sido eliminada exitosamente');
    }

    public function index(Request $request)
    {
        $cotizacion = Cotizacion::with('cliente')->with(['planess','planes.plan']);
        $recordsTotal = Cotizacion::all()->count();
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
            'cedula' => 'cliente.cedula',
            'nombres' => 'cliente.nombres',
            'apellidos' => 'cliente.apellidos',
            'fecha_email' => 'fecha_email',
            'created_at' => 'created_at',
            'total' => 'total'
        ];

        if( !empty(trim($search)) ) {
            $cotizacion->where('id','like', '%'.$search.'%')
                ->orwhere('fecha_email','like', '%'.$search.'%')
                ->orwhere('created_at','like', '%'.$search.'%')
                ->orwhere('total','like', '%'.$search.'%')
                ->orwhereHas('cliente', function($q) use ($search){
                    $q->where('cedula','like', '%'.$search.'%')
                        ->orwhere('nombres','like', '%'.$search.'%')
                        ->orwhere('apellidos','like', '%'.$search.'%');
                });
        }
   
        if( isset($orderCol) && isset($columns[$orderCol]) ) {
            $cotizacion->orderBy($columnNames[$columns[$orderCol]['data']], $orderDir);
        }

        $recordsFiltered = $cotizacion->count();

        if( intval($start) > 0 ) {
            $cotizacion->skip($start);
        }
        if( intval($length) > 0 ) {
            $cotizacion->take($length);
        }

        $cotizacion = $cotizacion->get();
        $cotizacion = $cotizacion->toArray();


        $response['cotizacion'] = $cotizacion;
        $response['draw'] = $draw;
        $response['recordsTotal'] = $recordsTotal;
        $response['recordsFiltered'] = $recordsFiltered;

        return response()->success($response);

        //return response()->success(compact('cotizacion'));
    }
    public function indexProspectos()
    {
        $prospecto = Cotizacion::where('estatus', 0)->with('cliente')->with('planess')->get();

        return response()->success(compact('prospecto'));
    }

    //Filtrado
    public function search(Request $request)
    {
        $cotizacion = Cotizacion::where('id','like',$request->data.'%')
            ->orWhere('created_at','like',$request->data.'%')
            ->orWhere('fecha_expiracion','like',$request->data.'%')
            ->orWhere('fecha_email','like',$request->data.'%')
            ->orWhere('email_enviado','like',$request->data.'%')
            ->orWhereHas('prospecto',function ($query) use($request){
                $query->where('nombres','like',$request->data.'%')
                    ->orWhere('apellidos','like',$request->data.'%')
                    ->orWhere('cedula','like',$request->data.'%')
                    ->orWhere('email','like',$request->data.'%');
            })->orWhereHas('planes',function ($query) use($request){
                $query->where('nombre','like',$request->data.'%');
            })
            ->with('prospecto')
            ->with('planes')
            ->get();

        return response()->success(compact('cotizacion'));
    }

    public function mailSend($id)
    {
        $cotizacion = Cotizacion::where('id',$id)
            ->with('planess')
            ->with('cliente')
            ->first();

        if (!$cotizacion){
            return response()->json('La cotizacion no existe',404);
        }

        try{
            Mail::send('emails.cotizacion', ['cotizacion' => $cotizacion], function ($m) use ($cotizacion) {
                $m->to($cotizacion->cliente->email, strtoupper($cotizacion->cliente->nombres.' '.$cotizacion->cliente->apellidos))
                    ->subject('Cotizacion #'.$cotizacion->id);
            });

            $cotizacion->fecha_email = Carbon::now()->toDateString();
            $cotizacion->save();

        }catch(\Exception $e){
            return response()->error('A ocurrio un error al intentar enviar la cotización por correo: '.$e->getMessage());
        }

        return response()->success('El correo ha sido enviado exitosamente');
    }
}
