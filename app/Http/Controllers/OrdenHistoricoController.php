<?php

namespace App\Http\Controllers;

use App\Orden;
use App\OrdenDetalleHistorico;
use App\OrdenHistorico;
use App\Cocina;
use App\Existencia;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Proveedor;
use Illuminate\Support\Facades\DB;

//use Carbon;

class OrdenHistoricoController extends Controller
{
    public function store(Request $request)
    {
        if ($request->planificacion_id){
            return $this->storeOcAutomatica($request);
        }else{
            return $this->storeOcManual($request);
        }
    }

    public function storeOcAutomatica(Request $request)
    {
        $this->validate($request,[
            'detalles.*.insumo_id' => 'required',
            'detalles.*.cantidad' => 'required',
        ]);

        $proveedor = Proveedor::all();

        foreach ($proveedor as $p) {

            $_insumos = [];

            foreach ($request->insumos as $insumo){

                $_insumo = $p->insumos()->where('insumo_id',$insumo['insumo_id'])->first();

                if ($_insumo){

                    $_insumos[] = [
                        'insumo_id' => $_insumo->id,
                        'unidad' => $_insumo->unidad_compra,
                        'cantidad' => $insumo['cantidad'],
                        'precio_unitario' => $_insumo->pivot->precio,
                        'total' => $_insumo->pivot->precio * $insumo['cantidad']
                    ];
                }
            }

            if ( $_insumos > 0 ){

                $fecha = ( $request->fecha ? $request->fecha : Carbon::now()->toDateString() );

                $orden = OrdenHistorico::create([
                    'descripcion' => $request->descripcion,
                    'fecha' => $fecha,
                    'planificacion_id' => $request->planificacion_id,
                    'orden_produccion_id' => $request->orden_produccion_id,
                    'proveedor_id' => $p->id,
                    'estatus' => "borrador",
                ]);

                $orden->detalles()->createMany($_insumos);
            }
        }

        return response()->success('Orden creada exitosamente');
    }

    public function storeOcManual(Request $request)
    {
        $this->validate($request,[
            'detalles.*.insumo_id' => 'required',
            'detalles.*.cantidad' => 'required',
        ]);

        foreach ($request->proveedores as $p) {

            $proveedor = Proveedor::find($p['proveedor_id']);

            $_insumos = [];

            foreach ($p['insumos'] as $insumo){

                $_insumo = $proveedor->insumos()->where('insumo_id',$insumo['insumo_id'])->first();

                if ($_insumo){

                    $_insumos[] = [
                        'insumo_id' => $_insumo->id,
                        'unidad' => $_insumo->unidad_compra,
                        'cantidad' => $insumo['cantidad'],
                        'precio_unitario' => $_insumo->pivot->precio,
                        'total' => $_insumo->pivot->precio * $insumo['cantidad']
                    ];
                }
            }

            if ( $_insumos > 0 ){

                $fecha = ( $request->fecha ? $request->fecha : Carbon::now()->toDateString() );

                $orden = OrdenHistorico::create([
                    'descripcion' => $request->descripcion,
                    'fecha' => $fecha,
                    'planificacion_id' => $request->planificacion_id,
                    'proveedor_id' => $proveedor->id
                ]);

                $orden->detalles()->createMany($_insumos);
            }
        }

        return response()->success('Orden creada exitosamente');
    }

    public function getOrden($id)
    {
        //ubico OC con planificacion para obtener id del centro de produccion
        $orden = OrdenHistorico::where('id',$id)->with('proveedor','planificacion','detalles','detalles.unidad_medida','detalles.insumo.unidad_compra')->first();

        if (!$orden){
            return response()->error("La orden de compra no existe");
        }

       
        $data = array ();
        $data["orden"] = $orden;
        $data["centro"] = $orden->planificacion->centro->id;


        return response()->success(compact('data'));
    }

    public function validarInsumos($id)
    {
        //ubico OC con planificacion para obtener id del centro de produccion
        $orden = OrdenHistorico::where('id',$id)->with('detalles','detalles.unidad_medida','planificacion')->first();

        if (!$orden){
            return response()->error("La orden de compra no existe");
        }

        $_insumos = [];

        //almaceno los id de los insumos de OC en un array
        foreach ($orden->detalles->toArray() as $o){
            $_insumos[] = $o['insumo_id'];
        }

        //ubico las bodegas perteneciente al centro de produccion
        $bodegas = $this->bodegasPertenecenACentro($orden->planificacion->centro_id);

        //Obtengo el detalle de la orden
        $insumos = OrdenDetalleHistorico::with([
            'insumo' => function($q){ $q->select('id','nombre'); },
            'unidad_medida' => function($q){ $q->select('id','nombre'); }

        ])
            ->where('orden_historico_id',$id)
            ->whereIn('insumo_id',$_insumos)
            ->get(['insumo_id','unidad','precio_unitario','cantidad','total']);


        //anexo existencia_id (si hay existencia) a cada insumo de OC para que se pueda hacer SHOW en la existencia
        foreach ($insumos as $i){

            $stock = $this->existenciaNoBodegas($bodegas,$i->insumo_id);
           
            if (count($stock) > 0){
                $i->existencia = true;
            }else{
                $i->existencia = false;
            }
        }
        
        $data = array ();
        $data["insumos"] = $insumos;
        $data["orden"] = $orden;
        $data["centro"] = $orden->planificacion->centro->id;

       // $insumos = array_add($insumos,'centro',$orden->planificacion->centro->id);

        return response()->success(compact('data'));
    }

    private function bodegasPertenecenACentro($centro)
    {
        //ubico las bodegas perteneciente al centro de produccion
        $cocinas = Cocina::where('centro_produccion_id',$centro)->with('bodegas')->get();

        $bodegas = [];

        //almaceno los id de las bodegas en un array
        foreach ($cocinas as $cocina) {
            foreach ($cocina->bodegas as $b) {
                $bodegas[] = $b->id;
            }
        }

        return $bodegas;
    }

    private function existenciaNoBodegas(Array $bodegas,$insumo)
    {   

        //filtro el stock de insumos de OC en las bodegas que NO pertenecen al centro de produccion que genero la OC
        $stock = Existencia::where('cantidad','>',0)
            ->where('insumo_id',$insumo)
            ->whereNotIn('bodega_id',$bodegas)
            ->get();

        return $stock;
    }

   /* public function existencia($insumoId, $centroId)
    {
        $existencia = Existencia::where('insumo_id',$insumoId)->get();
        return response()->success(compact('existencia'));
    }*/

    public function existencia($insumoId, $centroId)
    {
        $bodegas = $this->bodegasPertenecenACentro($centroId);

        $existencia = DB::table('existencias')
            ->join('insumos','existencias.insumo_id','=','insumos.id')
            ->join('unidad_medidas','existencias.unidad','=','unidad_medidas.id')
            ->join('bodegas','existencias.bodega_id','=','bodegas.id')
            ->select([
                'existencias.id as id',
                'existencias.bodega_id',
                'existencias.seccion_id',
                'existencias.posicion_id',
                'existencias.id as id',
                'insumos.nombre as insumo',
                'insumos.id as insumo_id',
                'cantidad',
                'unidad_medidas.id as unidad_id',
                'unidad_medidas.nombre as unidad',
                'bodegas.nombre as bodega',
                'bodegas.cocina_id as cocina'
            ])
            ->where('existencias.insumo_id',$insumoId)
            ->Where('cantidad','>',0)
            ->whereNotIn('bodega_id',$bodegas)
            ->get();

        foreach ($existencia as $e){
            $cocina = Cocina::where('id',$e->cocina)->with('centro')->first();
            $e->cocina = $cocina->nombre;
            $e->centro = $cocina->centro->nombre;
        }

        return response()->success(compact('existencia'));
    }

    public function index()
    {
        $orden = OrdenHistorico::whereNull('planificacion_id')->with([
            "proveedor" => function($q) { $q->select('id','nombre','apellido'); }
        ])->get();

        return response()->success(compact('orden'));
    }

    public function eliminarInsumoOC($ordenId,$insumoId)
    {
        $ordenHist = OrdenDetalleHistorico::where(['orden_historico_id' => $ordenId, 'insumo_id' => $insumoId])->first();

        if (!$ordenHist){
            return response()->error('Orden no encontrada');
        }

        $ordenHist->estatus = 'eliminado';
        $ordenHist->save();

        return response()->success('Insumo eliminado de la orden de compra');
    }

    public function aprobarOC($id)
    {
        $ordenHistorico = OrdenHistorico::where('id',$id)->with([
            'detalles' => function($q){ $q->where('estatus','por comprar'); }
        ])->first();

        if (!$ordenHistorico){
            return response()->error('Orden no encontrada');
        }

        try{
            DB::transaction(function() use($ordenHistorico){

                $ordenHistorico->estatus = "aprobada";
                $ordenHistorico->save();

                $orden = Orden::create([
                    "planificacion_id" => $ordenHistorico->planificacion_id,
                    "proveedor_id" => $ordenHistorico->proveedor_id,
                    "descripcion" => $ordenHistorico->descripcion ? $ordenHistorico->descripcion : " ",
                    "fecha" => Carbon::now()->toDateString(),
                    "estatus" => "aprobada",
                    "orden_historico_id" => $ordenHistorico->id,
                ]);

                foreach ($ordenHistorico->detalles as $detalle){
                    $detalle->orden_id = $orden->id;
                    $orden->detalles()->create($detalle->toArray());
                }
            });
        }catch (\Exception $e){
            return response()->error('Error '.$e->getMessage());
        }

        return response()->success('La orden de compra ha sido aprobada');
    }

    public function OCAprobadas($id)
    {
        $ordenHistorico = OrdenHistorico::where('id',$id)->with([
            "proveedor" => function($q){ $q->select('id','nombre','apellido'); },
            "detalles" => function($q) { $q->select('id','insumo_id','unidad','precio_unitario','cantidad','total','estatus','orden_historico_id')->orderBy('estatus','desc'); }  ,
            "detalles.insumo" => function($q){ $q->select('id','nombre'); },
            "detalles.unidad_medida" => function($q){ $q->select('id','nombre'); },
            "orden" => function($q){ $q->select('id','descripcion','fecha','estatus','orden_historico_id'); },
            "orden.detalles" => function($q){ $q->select('id','insumo_id','unidad','precio_unitario','cantidad','total','orden_id'); },
            "orden.detalles.insumo" => function($q){ $q->select('id','nombre'); },
            "orden.detalles.unidad_medida" => function($q){ $q->select('id','nombre'); },
        ])->get(['id','planificacion_id','orden_produccion_id','proveedor_id','descripcion','fecha','estatus']);

        if (!$ordenHistorico){
            return response()->error('Orden no encontrada');
        }

        return response()->success(compact('ordenHistorico'));
    }

    public function destroy($id)
    {
        $ordenHistorico = OrdenHistorico::find($id);

        if (!$ordenHistorico){
            return response()->error('Orden no encontrada');
        }

        $ordenHistorico->delete();

        return response()->success('Historico de orden ha sido eliminado');
    }

    public function descartarOC($id)
    {
        $ordenHistorico = OrdenHistorico::find($id);

        if (!$ordenHistorico){
            return response()->error('Orden no encontrada');
        }

        $ordenHistorico->estatus = "descartada";
        $ordenHistorico->save();

        return response()->success('Orden de compra descartada');
    }
}
