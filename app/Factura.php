<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = [
        'cliente_id',
        'cotizacion_id',
        'sub_total',
        'porcentaje_descuento',
        'descuento_total',
        'total',
        'iva',
    ];

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function cotizacion()
    {
        return $this->belongsTo('App\Cotizacion');
    }

    public function metodos()
    {
        return $this->belongsToMany('App\MetodosPago','factura_metodo','factura_id','metodo_pago_id');
    }

    public function planes()
    {
        return $this->belongsToMany('App\Plan','factura_plan','factura_id','plan_id')
            ->withPivot('cantidad','fecha_activacion','fecha_expiracion','cliente_id','beneficiario');
    }

    public function credito()
    {
        return $this->hasOne('App\Credito');
    }

    public function cheque()
    {
        return $this->hasMany('App\Cheque');
    }

    public function deposito()
    {
        return $this->hasMany('App\Deposito');
    }

    public function transferencia()
    {
        return $this->hasMany('App\Transferencia');
    }

    public function tarjeta_credito()
    {
        return $this->hasMany('App\TarjetaCredito');
    }
/*
    public function pais()
    {
        return $this->belongsTo('App\Country');
    }

    public function provincia()
    {
        return $this->belongsTo('App\Province');
    }

    public function ciudad()
    {
        return $this->belongsTo('App\City');
    }

    public function zona()
    {
        return $this->belongsTo('App\Zona');
    }
*/
}
