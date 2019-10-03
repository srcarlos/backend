<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
  protected $table = 'provinces';
  public $timestamps = false;
  protected $fillable = ['id' , 'name', 'country_id'];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
  }


  public function country()
  {
  	return $this->belongsTo(Country::class);
  }
  
  public function cities()
  {
  	return $this->hasMany(City::class);
  }

}
