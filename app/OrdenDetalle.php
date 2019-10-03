<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenDetalle extends Model
{
    protected $fillable = ['insumo_id','unidad','precio_unitario','cantidad','total','orden_id'];

    public function orden()
    {
        return $this->belongsTo('App\Orden');
    }

    public function insumo()
    {
        return $this->belongsTo('App\Insumo');
    }


    public function unidad_medida()
    {
        return $this->belongsTo('App\UnidadMedida','unidad');
    }
}
