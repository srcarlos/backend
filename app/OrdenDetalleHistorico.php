<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenDetalleHistorico extends Model
{
    protected $fillable = ['insumo_id','unidad','precio_unitario','cantidad','total','estatus','orden_historico_id'];


    public function detalles()
    {
        return $this->belongsTo('App\OrdenHistorico');
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
