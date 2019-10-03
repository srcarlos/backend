<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsumoProveedor extends Model
{
    protected $table = 'insumo_proveedor';

    protected $fillable = ['insumo_id','proveedor_id','precio'];
}
