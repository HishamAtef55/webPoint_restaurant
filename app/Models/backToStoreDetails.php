<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class backToStoreDetails extends Model
{
    use HasFactory;
    protected  $table ='stock_back_to_store_details';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
}
