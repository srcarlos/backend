<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
  protected $table = 'countries';
  public $timestamps = false;
  protected $fillable = ['id' ,'shortname', 'name'];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
  }

  public function provinces()
  {
  	return $this->hasMany(Province::class);
  }
}
