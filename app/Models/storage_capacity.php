<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class storage_capacity extends Model
{
    use HasFactory;
    protected  $table ='storage_capacities';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
}
