<?php namespace App;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanificacionProduccion extends Model {
   // use SoftDeletes;
     public $timestamps = false;
    protected $prefix;
    protected $table = 'planificacion_produccion';
    protected $fillable = [
        'planificacion_id',
        'dia',
        'turno_id',
        'status',
    ];
     public function detalle()
    {
        return $this->hasMany('App\PlanificacionProduccionPlato','production_id' );
    }
    public function turno()
    {
        return $this->hasOne('App\Turno','id',"turno_id" );
    }

    public function ordenes_ingreso()
    {
        return $this->hasMany('App\OrdenIngreso','orden_produccion_id');
    }

    public function ordenes_compra()
    {
        return $this->hasMany('App\Orden','orden_produccion_id');
    }

    public function ordenes_compra_historico()
    {
        return $this->hasMany('App\OrdenHistorico','orden_produccion_id');
    }


}
