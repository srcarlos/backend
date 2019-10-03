<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenHistorico extends Model
{
    protected $fillable = ['planificacion_id','orden_produccion_id','proveedor_id','descripcion','fecha','estatus'];

    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor','proveedor_id');
    }

    public function planificacion()
    {
        return $this->belongsTo('App\Planificacion','planificacion_id');
    }

    public function detalles()
    {
        return $this->hasMany('App\OrdenDetalleHistorico');
    }

    public function orden()
    {
        return $this->hasOne('App\Orden');
    }

    public function orden_produccion()
    {
        return $this->belongsTo('App\PlanificacionProduccion','orden_produccion_id');
    }
}
