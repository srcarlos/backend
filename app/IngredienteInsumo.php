<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IngredienteInsumo extends Model
{
    protected $table = 'ingrediente_insumo';

    protected $fillable = ['ingrediente_id','insumo_id','cantidad'];


    public function insumos() {
        return $this->belongsTo('App\Insumo','insumo_id');
      }
}
