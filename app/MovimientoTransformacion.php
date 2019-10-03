<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovimientoTransformacion extends Model
{
    protected $table = 'movimiento_transformacion';

    protected $fillable = [
        'bodega_id',
        'seccion_id',
        'posicion_id',
        'fecha',
        'observacion',
        'insumotrans_id',
        'cantidad',
        'unidad',
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

    public function insumo_transformado()
    {
        return $this->belongsTo('App\Insumo','insumotrans_id');
    }

    public function existencia_trans()
    {
        return $this->hasMany('App\Existencia','movimientotrans_id');
    }

    public function historicos()
    {
        return $this->hasMany('App\TransformacionHistorico','movimiento_transformacion');
    }
}
