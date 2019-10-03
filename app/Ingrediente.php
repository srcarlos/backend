<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    protected $table = 'ingredientes';

    protected $fillable = [
        'nombre',
        'category_id',
    ];

    public function category()
    {
        return $this->hasMany('App\Categoy');
    }

    public function insumos()
    {
        return $this->belongsToMany('App\Insumo')->withPivot('cantidad');
    }
}
