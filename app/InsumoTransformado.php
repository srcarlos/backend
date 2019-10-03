<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsumoTransformado extends Model
{
    protected $table = 'insumos_transformados';

    protected $fillable = [
        'nombre',
        'equivalencia',
        'costo_unidad_produccion',
        'unidad_produccion',
    ];

    public function unidad_medida()
    {
        return $this->belongsTo('App\UnidadMedida','unidad_produccion');
    }

    public function insumos()
    {
        return $this->belongsToMany('App\Insumo','insumotrans_insumo','insumo_trans_id','insumo_id')
            ->withPivot('cantidad');
    }

    public function bodegas()
    {
        return $this->belongsToMany('App\Bodega','existencias_trans','insumo_trans','bodega_id')
            ->withPivot('cantidad');
    }

    public function proveedores()
    {
        return $this->belongsToMany('App\Proveedor','insumotransformado_proveedor','insumotrans_id','proveedor_id')->withPivot('precio');
    }

    public function movimiento_transformacion()
    {
        return $this->hasMany('App\MovimientoTransformacion','insumotrans_id')->withPivot('precio');
    }
}
