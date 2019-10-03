<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenIngreso extends Model
{
    protected $fillable = ['orden_compra_id','orden_produccion_id','bodega_id'];

    public function orden_compra()
    {
        return $this->belongsTo('App\Orden','orden_compra_id');
    }

    public function detalles()
    {
        return $this->hasMany('App\OrdenIngresoDetalle');
    }

    public function bodega()
    {
        return $this->belongsTo('App\Bodega');
    }

    public function orden_produccion()
    {
        return $this->belongsTo('App\PlanificacionProduccion','orden_produccion_id');
    }
}
