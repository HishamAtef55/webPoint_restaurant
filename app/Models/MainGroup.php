<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainGroup extends Model
{
    use HasFactory;
    protected $table = 'stock_main_groups';
    protected $guarded =[];
    protected $hidden = ['created_at','updated_at'];
}
