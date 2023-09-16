<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stock_subunit extends Model
{
    use HasFactory;
    protected  $table ='stock_subunits';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];

}
