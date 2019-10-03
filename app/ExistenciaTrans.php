<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExistenciaTrans extends Model
{
    protected $table = 'existencia_trans';

    protected $fillable = [
        'posicion_id',
        'insumo_id',
        'bodega_id',
        'seccion_id',
        'cantidad',
        'unidad',
        'movimientotrans_id',
    ];

    public function posicion()
    {
        return $this->belongsTo('App\Posicion');
    }

    public function insumos()
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

    public function unidad_medida()
    {
        return $this->belongsTo('App\UnidadMedida','unidad');
    }

    public function movimientotrans()
    {
        return $this->belongsTo('App\MovimientoTransformacion','movimientotrans_id');
    }
}
