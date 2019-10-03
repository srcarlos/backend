<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    protected $table = "secciones";

    protected $fillable = ['nombre','codigo','bodega_id'];

    public function bodega()
    {
        return $this->belongsTo('App\Bodega');
    }

    public function posiciones()
    {
        return $this->hasMany('App\Posicion');
    }
}
