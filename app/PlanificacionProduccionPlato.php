<?php namespace App;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanificacionProduccionPlato extends Model {
    //use SoftDeletes;
   public $timestamps = false;

    protected $prefix;
	protected $table = 'planificacion_produccion_platos';
    protected $fillable = [
    	'production_id',
    	'platos_id',
        'cantidad'
    ];
  
    public function platos()
    {
        return $this->hasOne('App\Plato','id', 'platos_id' );
    }

    public function insumos()
    {
        return $this->hasMany('App\PlanificacionProduccionInsumo','produccion_plato_id' );
    }
}
