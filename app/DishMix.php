<?php namespace App;



use Illuminate\Database\Eloquent\Model;

class DishMix extends Model {
    protected $prefix;
	protected $table = 'dishes_mixes';
    protected $fillable = [
        'dish_id',
        'mix_id',
        'qty'
    ];
}