<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    protected $table = 'bodegas';

    protected $fillable = ['codigo','nombre','ubicacion','cocina_id','tipo'];

    public function cocina()
    {
        return $this->belongsTo('App\Cocina');
    }

    public function secciones()
    {
        return $this->hasMany('App\Seccion');
    }

    public function insumos()
    {
        return $this->belongsToMany('App\Insumo','existencias')
            ->withPivot('cantidad');
    }

    public function insumos_transformados()
    {
        return $this->belongsToMany('App\InsumoTransformado','existencias_trans','bodega_id','insumo_trans')
            ->withPivot('cantidad');
    }
}
