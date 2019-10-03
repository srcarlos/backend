<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    protected $table = 'unidad_medidas';

    protected $fillable = ['nombre','abreviacion'];

    public function insumos()
    {
        return $this->hasMany('App\Insumo','unidad_compra');
    }

    public function insumos_transformados()
    {
        return $this->hasMany('App\InsumoTransformado','unidad_produccion');
    }
}
