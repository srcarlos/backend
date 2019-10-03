<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TurnoPlan extends Model
{
    protected $table = 'turnos_plan';

    protected $fillable = [
        'turno_id',
        'plan_id',
        'cantidad', // capacidad de platos. 
        'dutracion'
    
    ];


    public function turno()
    {
        return $this->belongsTo('App\Turno');
    }


}
