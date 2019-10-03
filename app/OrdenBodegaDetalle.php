<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenBodegaDetalle extends Model
{
    protected $table = 'ordenbodega_detalle';

    protected $fillable = ['insumo_id','unidad','proveedor_id','precio_unitario','cantidad','total','orden_id'];

    public function orden()
    {
        return $this->belongsTo('App\OrdenBodega');
    }

    public function insumo()
    {
        return $this->belongsTo('App\Insumo');
    }

    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }

    public function unidad_medida()
    {
        return $this->belongsTo('App\UnidadMedida','unidad');
    }
}
