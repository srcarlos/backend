<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
        'persona',
        'ci',
        'nombre',
        'apellido',
        'direccion',
        'telefono',
        'identificacion',
        'correo',
        'tiempo_entrega',
        'activo',
        'credito',
        'dias',
        'monto_maximo',
        
    ];

    public function contactos()
    {
        return $this->hasMany('App\Contacto');
    }

    public function cuentas()
    {
        return $this->hasMany('App\Cuenta');
    }

    public function metodos()
    {
        return $this->belongsToMany('App\MetodosPago','proveedor_metodo','proveedor_id','metodo_pago_id');
    }

    public function insumos()
    {
        return $this->belongsToMany('App\Insumo')->withPivot('precio');
    }

    public function insumos_transformados()
    {
        return $this->belongsToMany('App\InsumoTransformado','insumotransformado_proveedor','proveedor_id','insumotrans_id')->withPivot('precio');
    }

    public function orden_compra()
    {
        return $this->hasMany('App\Orden');
    }
}
