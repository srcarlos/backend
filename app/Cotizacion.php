<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    protected $fillable = [
        'compania_id',
        'prospecto',
        'fecha_activacion',
        'fecha_expiracion',
        'fecha_email',
        'email_enviado',
        'descuento_porcentaje',
        'descuento_total',
        'sub_total',
        'total',
        'estatus',
        'pais_id',
        'provincia_id',
        'ciudad_id',
        'calle1',
        'calle2',
        'casa_nro',
        'zona_id',
        'referencias'
    ];

    public function beneficiario() {
      return $this->belongsTo('App\Cliente', 'prospecto', 'id');
    }

     public function planes()
    {
        return $this->hasMany('App\CotizacionPlan');
    }

    public function factura()
    {
        return $this->hasOne('App\Factura');
    }

     public function pospecto()
    {
        return $this->hasOne('App\Cliente','id','prospecto','cliente_id');
    }

    public function credito()
    {
        return $this->hasMany('App\Credito');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente','prospecto');
    }

    public function planess()
    {
        return $this->belongsToMany('App\Plan','cotizaciones_planes','cotizacion_id','plan_id')
            ->withPivot('cantidad');
    }


    public function pais()
    {
        return $this->belongsTo('App\Country','pais_id');
    }

    public function provincia()
    {
        return $this->belongsTo('App\Province','provincia_id');
    }

    public function ciudad()
    {
        return $this->belongsTo('App\City','ciudad_id');
    }
}
