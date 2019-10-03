<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compania extends Model
{
    protected $table = 'companias';

    protected $fillable = [
        'nombre',
        'razon_social',
        'direccion',
        'sitio_web',
        'telf_particular',
        'telf_oficina',
        'logo',
        'rep_legal',
        'ruc',
        'ruc_rep_legal',
        'acceso_mod_ventas',
        'cantidad_platos'
    ];
    public function centros(){
        return $this->hasMany('App\CentroProduccion');
    }
}
