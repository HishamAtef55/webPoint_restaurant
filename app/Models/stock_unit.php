<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stock_unit extends Model
{
    use HasFactory;
    protected  $table ='stock_units';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
}
