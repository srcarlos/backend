<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $fillable = ['tipo','banco','nro','credito','dias','monto_maximo','proveedor_id'];

    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }
}
