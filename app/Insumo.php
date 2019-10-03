<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    protected $table = 'insumos';

    protected $fillable = [
        'nombre',
        'tipo',
        'unidad_compra',
        'equivalencia',
        'unidad_produccion',
        'impuesto_id',
        'marca',
        'costo_unidad',
        'costo_unidad_produccion',
    ];

    public function impuesto()
    {
        return $this->belongsTo('App\Impuesto');
    }

    public function alergicos()
    {
        return $this->belongsToMany('App\Cliente','alergias','cliente_id','insumo_id');
    }

    public function bodegas()
    {
        return $this->belongsToMany('App\Bodega','existencias')
            ->withPivot('cantidad');
    }

    public function posiciones()
    {
        return $this->belongsToMany('App\Posicion','existencias')
            ->withPivot('cantidad');
    }

    public function unidad_produccion()
    {
        return $this->belongsTo('App\UnidadMedida','unidad_produccion');
    }

    public function unidad_compra()
    {
        return $this->belongsTo('App\UnidadMedida','unidad_compra');
    }

    public function insumos_transformados()
    {
        return $this->belongsToMany('App\InsumoTransformado','insumotrans_insumo','insumo_id','insumo_trans_id');
    }

    public function ingredientes()
    {
        return $this->belongsToMany('App\Ingrediente')->withPivot('cantidad');
    }

    public function proveedores()
    {
        return $this->belongsToMany('App\Proveedor')->withPivot('precio');
    }

    public function insumosbase()
    {
        return $this->belongsToMany('App\Insumo','base_insumos_transformados','insumotrans_id','insumo_id')
            ->withPivot('cantidad','equivalencia');
    }

    public function ajustes()
    {
        return $this->belongsToMany('App\MovimientoAjuste','ajuste_insumo','insumo_id','ajuste_id')
            ->withPivot('cantidad')
            ->withTimestamps();
    }
}
