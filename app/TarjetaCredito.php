<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TarjetaCredito extends Model
{
    protected $table = 'tarjetas_creditos';

    protected $fillable = [
        'titular',
        'tipo_tarjeta',
        'marca',
        'banco',
        'forma_pago',
        'monto',
        "factura_id",
    ];

    public function factura()
    {
        return $this->belongsTo('App\Factura');
    }
}
