<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsumoTransInsumo extends Model
{
    protected $table = 'insumotrans_insumo';

    protected $fillable = ['insumotrans_id','insumo_id','cantidad'];
}
