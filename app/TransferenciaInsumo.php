<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferenciaInsumo extends Model
{
    protected $fillable = [
        'bodega_sal',
        'seccion_sal',
        'posicion_sal',
        'insumo_sal',
        'cantidad_sal',
        'unidad',
        'bodega_ent',
        'seccion_ent',
        'posicion_ent',
        'movimientotransf_id',
    ];

    public function bodegaSal()
    {
        return $this->belongsTo('App\Bodega','bodega_sal');
    }

    public function seccionSal()
    {
        return $this->belongsTo('App\Seccion','seccion_sal');
    }

    public function posicionSal()
    {
        return $this->belongsTo('App\Posicion','posicion_sal');
    }

    public function insumo()
    {
        return $this->belongsTo('App\Insumo','insumo_sal');
    }

    public function bodegaEnt()
    {
        return $this->belongsTo('App\Bodega','bodega_ent');
    }

    public function seccionEnt()
    {
        return $this->belongsTo('App\Seccion','seccion_ent');
    }

    public function posicionEnt()
    {
        return $this->belongsTo('App\Posicion','posicion_ent');
    }

    public function unidadMedida()
    {
        return $this->belongsTo('App\UnidadMedida','unidad');
    }

    public function movimiento_transf()
    {
        return $this->belongsTo('App\MovimientoTransferencia','movimientotransf_id');
    }


}
