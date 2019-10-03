<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CotizacionPlan extends Model
{
    protected $table = 'cotizaciones_planes';

    protected $fillable = ['plan_id','cantidad'];


      public function plan()
    {
        return $this->hasOne('App\Plan','id','plan_id');
    }
}
