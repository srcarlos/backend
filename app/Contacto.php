<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    protected $fillable = ['nombre','apellido','telefono','telefono2','proveedor_id'];

    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }
}
