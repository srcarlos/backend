<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    protected $fillable = ['nombre','estado'];

    public function clientes()
    {
        return $this->hasMany('App\Cliente');
    }

    public function motorizado() {
      return $this->hasOne('App\ZonaMotorizado','zona_id', 'id');
    }
}
