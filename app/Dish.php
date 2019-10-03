<?php namespace App;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dish extends Model {
    use SoftDeletes;

    protected $prefix;
	protected $table = 'dishes';
    protected $fillable = [
    	'name'
    ];
  
}