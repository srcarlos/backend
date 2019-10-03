<?php namespace App;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanificacionProduccionInsumo extends Model {
    //use SoftDeletes;
   public $timestamps = false;

    protected $prefix;
	protected $table = 'planificacion_produccion_insumos';
    protected $fillable = [
    	'insumo_id',
    	'produccion_plato_id',
        'cantidad'
    ];
  
    public function insumo()
    {
        return $this->hasOne('App\Insumo','id', 'insumo_id' );
    }
}
