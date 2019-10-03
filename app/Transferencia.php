<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transferencia extends Model
{
    protected $fillable = [
      'numero',
      'banco',
      'titular',
      'tipo_cuenta',
      'cta_nro',
      'monto',
      "factura_id",
    ];

    public function factura()
    {
        return $this->belongsTo('App\Factura');
    }
}
