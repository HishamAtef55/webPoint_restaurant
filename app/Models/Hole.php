<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hole extends Model
{
    protected $table = 'holes';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
}
