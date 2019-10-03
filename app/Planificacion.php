<?php namespace App;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Planificacion extends Model {
    use SoftDeletes;

    protected $prefix;
	protected $table = 'planificaciones';
    protected $fillable = [
    	'desde',
    	'hasta',
        'status',
        'total_platos',
    	'centro_id',
    	'cocina_id'
    ];
  
     public function centro()
    {
        return $this->hasOne('App\CentroProduccion','id', 'centro_id' );
    }

    public function cocina()
    {
        return $this->hasOne('App\Cocina','id', 'cocina_id' );
    }

     public function produccion()
    {
     
        return $this->hasMany('App\PlanificacionProduccion',"planificacion_id");
       
    }

    public function ordenesHistorico()
    {
        return $this->hasMany('App\OrdenHistorico','planificacion_id');
    }

    public function ordenes()
    {
        return $this->hasMany('App\Orden','planificacion_id');
    }
}