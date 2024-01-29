<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class halkDetails extends Model
{
    use HasFactory;
    protected  $table ='stock_halk_details';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
}
