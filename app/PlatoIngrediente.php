<?php

// use Illuminate\Foundation\Auth\User as Authenticatable;
namespace App;

use Illuminate\Database\Eloquent\Model;

class PlatoIngrediente extends Model
{
  public $table = "plato_ingredientes";

  public $fillable = ['plato_id', 'ingrediente_id'];

  public function plato() {
    return $this->belongsTo('App\Plato', 'plato_id', 'id');
  }

  public function ingrediente() {
    return $this->belongsTo('App\Ingrediente', 'ingrediente_id', 'id');
  }
}