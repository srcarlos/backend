<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservaInsumo extends Model
{   
    protected $table = 'reserva_insumos';

    protected $fillable = [
        'bodega_id',
        'seccion_id',
        'posicion_id',
        'insumo_id',
        'cantidad',
        'unidad',
        'movimientoreserva_id',
    ];

    public function movimiento_reserva()
    {
        return $this->belongsTo('App\MovimientoReserva','movimientoreserva_id');
    }
}
