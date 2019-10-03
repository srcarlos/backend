<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supply extends Model {
    use SoftDeletes;

    protected $prefix;
	protected $table = 'supplies';
    protected $fillable = [
        'name',
        'material',
        'measure_unit_production',
        'equivalence',
        'measure_unit_purchase',
        'tax',
        'production_unit_price'
    ];
}