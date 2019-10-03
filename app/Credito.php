<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credito extends Model
{
    protected $fillable = [
        'dias',
        'fecha_expiracion',
        'cliente_id',
        "cotizacion_id",
        "factura_id",
    ];

    public function factura()
    {
        return $this->belongsTo('App\Factura');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function cotizacion()
    {
        return $this->belongsTo('App\Cotizacion');
    }
}
