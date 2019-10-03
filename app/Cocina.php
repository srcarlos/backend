<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cocina extends Model
{
    protected $table = 'cocinas';

    protected $fillable = [
        'nombre',
        'direccion',
        'responsable',
        'centro_produccion_id',
    ];

    public function _responsable()
    {
        return $this->belongsTo('App\User','responsable');
    }

    public function centro()
    {
        return $this->belongsTo('App\CentroProduccion','centro_produccion_id');
    }

    public function bodegas()
    {
        return $this->hasMany('App\Bodega');
    }

    public function planificaciones()
    {
        return $this->hasMany('App\Planificaciones', 'cocina_id', 'id');
    }
}
