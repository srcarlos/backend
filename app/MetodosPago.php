<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetodosPago extends Model
{
    protected $table = 'metodos_pagos';

    protected $fillable = ['nombre','descripcion','estado'];

    public function proveedores()
    {
        return $this->belongsToMany('App\Proveedor','proveedor_metodo','proveedor_id','metodo_pago_id');
    }
}
