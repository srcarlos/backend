<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransformacionHistorico extends Model
{
    protected $table = 'transformacion_historicos';

    protected $fillable = [
        'insumo_id',
        'seccion_id',
        'posicion_id',
        'disponibilidad',
        'cant_req',
        'unidad',
        'movimiento_transformacion'
    ];
    public function insumo()
    {
        return $this->belongsTo('App\Insumo');
    }

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

    public function unidad_medida()
    {
        return $this->belongsTo('App\UnidadMedida','unidad');
    }

    public function movimiento()
    {
        return $this->belongsTo('App\MovimientoTransformacion','movimiento_transformacion');
    }
}
