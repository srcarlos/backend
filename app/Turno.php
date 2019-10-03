<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    protected $table = 'turnos';

    protected $fillable = [
        'cantidad', // capacidad de platos. 
        'turno',
        'desde',
        'hasta'
    ];

}
