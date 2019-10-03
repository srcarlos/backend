<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovimientoAjuste extends Model
{
    protected $fillable = [
        'cocina_id',
        'bodega_id',
        'seccion_id',
        'posicion_id',
        'accion',
        'tipo',
        'observacion',
    ];

    public function cocina()
    {
        return $this->belongsTo('App\Cocina');
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
    public function insumos()
    {
        return $this->belongsToMany('App\Insumo','ajuste_insumo','ajuste_id','insumo_id')
            ->withPivot('cantidad')
            ->withTimestamps();
    }
}
