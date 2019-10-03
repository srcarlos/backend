<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientePlan extends Model
{
  public $table = "cliente_plan";

  public $fillable = [
    'cliente_id', 
    'plan_id', 
    'beneficiario',
    'fecha_activacion',
    'fecha_expiracion', 
    'suspender_desde', 
    'suspender_hasta', 
    'direccion_entrega', 
    'estado'];
  
  protected $casts = [
    'estado' => 'boolean'
  ];

  protected $appends = ['suspendido', 'activo'];

  public function getSuspendidoAttribute() {
    return $this->estado && $this->suspender_desde && $this->suspender_hasta;
    /*
    $now = date("Y-m-d");
    return $this->estado && $this->suspender_desde && $this->suspender_hasta &&
           ($this->suspender_desde <= $now && $this->suspender_hasta >= $now);*/
  }

  public function getActivoAttribute() {
    $now = date("Y-m-d");

    return $this->estado && !$this->suspendido && $this->fecha_activacion && $this->fecha_expiracion &&
           ($this->fecha_activacion <= $now && $this->fecha_expiracion >= $now);
  }


  public function cliente() {
    return $this->belongsTo('App\Cliente', 'cliente_id', 'id');
  }

  public function beneficiario() {
    return $this->belongsTo('App\Cliente', 'cliente_id', 'id');
  }

  public function plan() {
    return $this->belongsTo('App\Plan', 'plan_id', 'id');
  }


}