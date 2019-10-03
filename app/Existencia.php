<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Existencia extends Model
{
    protected $fillable = [
        'bodega_id',
        'seccion_id',
        'posicion_id',
        'insumo_id',
        'cantidad',
        'unidad',
        'movimientotrans_id'
    ];

    public function bodega()
    {
        return $this->belongsTo('App\Bodega');
    }

    public function seccion()
    {
        return $this->belongsTo('App\Seccion');
    }

    public function posicion()
    {
        return $this->belongsTo('App\Posicion');
    }

    public function insumo()
    {
        return $this->belongsTo('App\Insumo');
    }

    public function unidad()
    {
        return $this->belongsTo('App\UnidadMedida','unidad');
    }

    public function movimientotrans()
    {
        return $this->belongsTo('App\MovimientoTransformacion','movimientotrans_id');
    }
}
