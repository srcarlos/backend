<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ZonaMotorizado extends Model
{
  public $table = "zona_motorizados";

  public $fillable = ['zona_id', 'motorizado_id'];

  public function motorizado() {
    return $this->belongsTo('App\Motorizado', 'motorizado_id', 'id');
  }

  public function zona() {
    return $this->belongsTo('App\Zona', 'zona_id', 'id');
  }
}