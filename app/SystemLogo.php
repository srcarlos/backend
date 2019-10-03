<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemLogo extends Model
{
    protected $table = 'system_logos';

    protected $fillable = ['nombre','extension'];
}
