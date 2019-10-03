<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovimientoTransferencia extends Model
{
    protected $fillable = ['orden_historico_id','estatus','observacion'];

    public function transferencias()
    {
        return $this->hasMany('App\TransferenciaInsumo','movimientotransf_id');
    }
}
