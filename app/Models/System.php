<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
  protected $table = 'informations_systems';
  protected $fillable=['name','id','icon'];
  protected $hidden = ['created_at','updated_at'];
}
