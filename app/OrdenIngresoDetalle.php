<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenIngresoDetalle extends Model
{
    protected $fillable = [
        'orden_ingreso_id',
        'insumo_id',
        'unidad',
        'precio_unitario',
        'cantidad',
        'total',
        'cantidad_ingresada',
        'cantidad_recibida',
        'seccion_id',
        'posicion_id',
    ];

    public function orden_ingreso()
    {
        return $this->belongsTo('App\OrdenIngreso');
    }

    public function insumo()
    {
        return $this->belongsTo('App\Insumo');
    }

    public function unidad_medida()
    {
        return $this->belongsTo('App\UnidadMedida','unidad');
    }

    public function seccion()
    {
        return $this->belongsTo('App\Seccion');
    }

    public function posicion()
    {
        return $this->belongsTo('App\Posicion');
    }
}
