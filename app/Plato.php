<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    protected $table = 'platos';

    protected $fillable = ['nombre','descripcion'];

    public function ingredientes() {
      return $this->hasMany('App\PlatoIngrediente','plato_id', 'id');
    }
}
