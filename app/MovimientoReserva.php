<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovimientoReserva extends Model
{
    protected $table = 'movimiento_reservas';

    protected $fillable = [
        'cocina_id',
        'centro_id',
        'production_id',
        'fecha'
    ];

    public function bodega()
    {
        return $this->belongsTo('App\Bodega');
    }

    public function centro()
    {
        return $this->belongsTo('App\Centro');
    }

     public function orden()
    {
        return $this->belongsTo('App\PlanificacionProduccion','production_id');
    }

   
}
