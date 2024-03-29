<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    protected $fillable = [
        "numero",
        "banco",
        "factura_id",
        "monto",
    ];

    public function factura()
    {
        return $this->belongsTo('App\Factura');
    }
}
