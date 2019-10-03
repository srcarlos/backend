<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UnidadMedida;
use App\Http\Requests;
use Barryvdh\DomPDF\Facade as PDF;
use DB;

class UnidadMedidaController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'nombre' => 'required',
            'abreviacion' => 'required',
        ]);

        UnidadMedida::create($request->all());

        return response()->success("Unidad de medida creada exitosamente");
    }

    public function show($id)
    {
        $unidad = UnidadMedida::find($id);

        if (!$unidad){
            return response()->json('La unidad de medida no existe',404);
        }

        return response()->success(compact('unidad'));
    }

    public function update(Request $request,$id)
    {
        $unidad = UnidadMedida::find($id);

        if (!$unidad){
            return response()->json('La unidad de medida no existe',404);
        }

        $this->validate($request,[
            'nombre' => 'required',
            'abreviacion' => 'required',
        ]);

        $unidad->nombre = $request->nombre;
        $unidad->abreviacion = $request->abreviacion;
        $unidad->save();

        return response()->success('La unidad de medida ha sido modificada exitosamente');
    }

    public function destroy($id)
    {
        $unidad = UnidadMedida::find($id);

        if (!$unidad){
            return response()->json('La unidad de medida no existe',404);
        }

        $unidad->delete();

        return response()->success('La unidad de medida ha sido eliminada exitosamente');
    }

    public function index(Request $request)
    {
        $unidad = DB::table('unidad_medidas');
        $recordsTotal = UnidadMedida::all()->count();
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
            'abreviacion' => 'abreviacion'
        ];

        if( !empty(trim($search)) ) {
            $unidad->where('id','like', '%'.$search.'%')
                ->orwhere('nombre','like', '%'.$search.'%')
                ->orwhere('abreviacion','like', '%'.$search.'%');
            $entro='entro';
        }
        if( !empty($orderCol) && isset($columns[$orderCol]) ) {
            $unidad->orderBy($columnNames[$columns[$orderCol]['data']], $orderDir);
        }

        $recordsFiltered = $unidad->count();

        if( intval($start) > 0 ) {
            $unidad->skip($start);
        }
        if( intval($length) > 0 ) {
            $unidad->take($length);
        }

        $unidad = $unidad->get();

        $response['unidad'] = $unidad;
        $response['draw'] = $draw;
        $response['recordsTotal'] = $recordsTotal;
        $response['recordsFiltered'] = $recordsFiltered;

        return response()->success($response);
    }

    public function search(Request $request)
    {
        $unidad = UnidadMedida::where('nombre','like',$request->data.'%')
            ->orWhere('abreviacion','like',$request->data.'%')
            ->get();

        return response()->success(compact('unidad'));
    }

    public function csv()
    {
        $unidad = UnidadMedida::all();

        $csv = new \Laracsv\Export();

        $csv->build($unidad, ['nombre','abreviacion',])->download('unidades_de_medida.csv');
    }

    public function pdf()
    {
        $unidades = UnidadMedida::all();

        $pdf = PDF::loadView('PDF.unidades_pdf',compact('unidades'));

        return $pdf->download('unidades_de_medida.pdf');
    }
}
