<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posicion extends Model
{
    protected $table = 'posiciones';

    protected $fillable = ['nombre','codigo','seccion_id'];

    public function seccion()
    {
        return $this->belongsTo('App\Seccion');
    }

    public function insumos()
    {
        return $this->belongsToMany('App\Insumo','existencias')
            ->withPivot('cantidad');
    }

    public function insumos_transformados()
    {
        return $this->belongsToMany('App\InsumoTransformado','existencia_trans','posicion_id','insumotrans_id')
            ->withPivot('cantidad');
    }
}
