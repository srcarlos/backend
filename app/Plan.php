<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{	
	//use SoftDeletes;

    protected $table = 'planes';

    protected $fillable = ['nombre','precio','almuerzo','cena','snack','duracion'];

    public function clientes()
    {
        return $this->belongsToMany('App\Cliente','factura_plan','plan_id','cliente_id')
            ->withPivot('cantidad','fecha_activacion','fecha_expiracion','factura_id','beneficiario');
    }

    public function beneficiarios()
    {
        return $this->belongsToMany('App\Cliente','factura_plan','plan_id','beneficiario');
    }

    public function cotizaciones()
    {
        return $this->belongsToMany('App\Cotizacion','cotizaciones_planes','cotizacion_id','plan_id');
    }

      public function turnos()
    {
        return $this->hasMany('App\TurnoPlan');
    }




    //protected $hidden = ['pivot'];
}
