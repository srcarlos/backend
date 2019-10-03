<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nombres',
        'apellidos',
        'cedula',
        'email',
        'celular',
        'convencional',
        'pais_id',
        'provincia_id',
        'ciudad_id',
        'calle1',
        'calle2',
        'casa_nro',
        'zona_id',
        'referencias',
    ];

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

    public function zona()
    {
        return $this->belongsTo('App\Zona');
    }

    public function alergias()
    {
        return $this->belongsToMany('App\Insumo','alergias','cliente_id','insumo_id');
    }

    public function planes()
    {
        return $this->belongsToMany('App\Plan','factura_plan','cliente_id','plan_id')
            ->withPivot('cantidad','fecha_activacion','fecha_expiracion','factura_id','beneficiario');
    }

    public function facturas()
    {
        return $this->hasMany('App\Factura');
    }

    public function creditos()
    {
        return $this->hasMany('App\Credito');
    }

    public function cotizacion()
    {
        return $this->belongsTo('App\Cotizacion');
    }
}
