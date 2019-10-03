<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Plato;
use App\PlatoIngrediente;
use App\Ingrediente;
use Barryvdh\DomPDF\Facade as PDF;

class PlatosController extends Controller
{

    public function store(Request $request)
    {
        $this->validate($request,[
            'nombre' => 'required|unique:platos,nombre',
            'descripcion' => 'required',
        ]);

        Plato::create($request->all());

        return response()->success("Plato creado exitosamente");
    }

    public function show($id)
    {
        $plato = Plato::with(['ingredientes.ingrediente'])->where('id',$id)->first();
        if (!$plato){
            return response()->json('El plato no existe',404);
        }
        return response()->success(compact('plato'));
    }

    public function update(Request $request,$id)
    {
        $plato = Plato::find($id);

        if (!$plato){
            return response()->json('El plato no existe',404);
        }

        $this->validate($request,[
            'nombre' => 'required|unique:platos,nombre,'.$plato->id,
            'descripcion' => 'required',
        ]);

        $plato->nombre = $request->nombre;
        $plato->descripcion = $request->descripcion;
        $plato->save();

        return response()->success('El plato ha sido modificado exitosamente');
    }

    public function destroy($id)
    {
        $plato = Plato::find($id);

        if (!$plato){
            return response()->json('El plato no existe',404);
        }

        $plato->delete();

        return response()->success('El plato ha sido eliminado exitosamente');
    }

    public function index(Request $request)
    {
        $plato = Plato::with(['ingredientes.ingrediente']);
        $recordsTotal = Plato::all()->count();
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
            'nombre' => 'nombre'
        ];

        if( !empty(trim($search)) ) {
            $plato->where('id','like', '%'.$search.'%')
                ->orwhere('nombre','like', '%'.$search.'%');
        }
        if( !empty($orderCol) && isset($columns[$orderCol]) ) {
            $plato->orderBy($columnNames[$columns[$orderCol]['data']], $orderDir);
        }

        $recordsFiltered = $plato->count();

        if( intval($start) > 0 ) {
            $plato->skip($start);
        }
        if( intval($length) > 0 ) {
            $plato->take($length);
        }

        $plato = $plato->get();
        $plato = $plato->toArray();

        $response['plato'] = $plato;
        $response['draw'] = $draw;
        $response['recordsTotal'] = $recordsTotal;
        $response['recordsFiltered'] = $recordsFiltered;

        return response()->success($response);
    }

    public function updateIngredientes(Request $request,$id) {
      $plato = Plato::find($id);
      if (!$plato){
          return response()->json('El plato no existe',404);
      }
      $tmp = [];
      $ingredientes = [];
      if (is_string($request->ingredientes)) {
        $tmp = json_decode($request->ingredientes);
        if (!is_array($tmp)) {
          $tmp = [];
        }
      } else if (is_array($request->ingredientes)) {
        $tmp = $request->ingredientes;
      }

      foreach($tmp as $key => $value) {
        $ingredientes[(int)$key] = (int) $value;
      }

      //Agregamos / Editamos
      foreach($ingredientes as $iid => $cantidad) {
        $ingrediente = Ingrediente::find($iid);
        if (!is_int($cantidad)) {
          $cantidad = (int) $cantidad;
        }
        if (!$ingrediente || $cantidad < 1) {
          unset($ingredientes[$iid]);
        }
        $current = PlatoIngrediente::where('plato_id', $id)->where('ingrediente_id', $iid)->first();
        if  (!$current) {
            $current = new PlatoIngrediente;
            $current->ingrediente_id = $iid;
            $current->plato_id = $id;
        }
        $current->cantidad = $cantidad;
        $current->save();
      }
      foreach(PlatoIngrediente::where('plato_id',$id)->get() as $ingrediente) {
        if (!isset($ingredientes[$ingrediente->ingrediente_id])) {
          $ingrediente->delete();
        }
      }

      return response()->success('success');
    }

    public function catalogo()
    {
        $platos = Plato::with('ingredientes.ingrediente')->get();

        //dd($platos->toArray());

        $pdf = PDF::loadView('PDF.platos_catalogo',compact('platos'));

        return $pdf->download('catalogo_platos.pdf');
    }
}
