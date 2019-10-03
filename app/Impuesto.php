<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Impuesto extends Model
{
    protected $table = 'impuestos';

    protected $fillable = ['nombre','descripcion','porcentaje'];

    public function insumos()
    {
        return $this->hasMany('App\Insumo');
    }
}
