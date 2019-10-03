<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposito extends Model
{
    protected $fillable = [
        "valor",
        "banco",
        "dep_nro",
        "cta_nro",
        "monto",
        "factura_id",
    ];

    public function factura()
    {
        return $this->belongsTo('App\Factura');
    }
}
