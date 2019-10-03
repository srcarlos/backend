<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mix extends Model {
    use SoftDeletes;

    protected $prefix;
	protected $table = 'mixes';
    protected $fillable = [
        'name',
        'category_id',
        'supply_id',
        'price'
    ];
}