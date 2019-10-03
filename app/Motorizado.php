<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Motorizado extends Model
{
    protected $fillable = ['nombre','estado'];

    public function zona() {
      return $this->hasOne('App\ZonaMotorizado', 'motorizado_id', 'id');
    }
}
