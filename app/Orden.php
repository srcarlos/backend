<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    protected $table = 'ordenes';

    protected $fillable = [
        'planificacion_id',
        'orden_produccion_id',
        'proveedor_id',
        'descripcion',
        'fecha',
        'estatus',
        'orden_historico_id'
    ];


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
        return $this->hasMany('App\OrdenDetalle');
    }

    public function historico()
    {
        return $this->belongsTo('App\OrdenHistorico');
    }

    public function orden_produccion()
    {
        return $this->belongsTo('App\PlanificacionProduccion','orden_produccion_id');
    }
}
