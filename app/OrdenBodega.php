<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenBodega extends Model
{
    protected $table = 'ordenes_bodegas';

    protected $fillable = ['descripcion','cocina_id','bodega_id','fecha','estatus'];

    public function detalles()
    {
        return $this->hasMany('App\OrdenBodegaDetalle','orden_id');
    }

    public function bodega()
    {
        return $this->belongsTo('App\Bodega');
    }

    public function cocina()
    {
        return $this->belongsTo('App\Cocina');
    }
}
