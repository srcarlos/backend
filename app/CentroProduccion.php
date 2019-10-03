<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CentroProduccion extends Model
{
    protected $table = 'centros_produccion';

    protected $fillable = [
        'nombre',
        'descripcion',
        'direccion',
        'responsable',
        'tlf_responsable',
        'compania_id'
    ];

    public function compania()
    {
        return $this->belongsTo('App\Compania');
    }

    public function cocinas()
    {
        return $this->hasMany('App\Cocina');
    }
}
