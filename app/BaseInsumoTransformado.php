<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseInsumoTransformado extends Model
{
    protected $table = 'base_insumos_transformados';

    protected $fillable = ['insumotrans_id','insumo_id','cantidad','equivalencia'];

    //protected $primaryKey = ['insumotrans_id','insumo_id'];

    //public $incrementing = false;

    public function insumotrans()
    {
        return $this->belongsTo('App\Insumo','insumotrans_id');
    }

    public function insumo()
    {
        return $this->belongsTo('App\Insumo','insumo_id');
    }
}
