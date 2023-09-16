<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Details_Wait_Order_m;
use App\Models\Extra_wait_order_m;

class Void_m extends Model
{
    protected  $table ='void_m';
    protected $guarded = [];

    public function Details()
    {
        return $this->hasMany(Details_Wait_Order_m::class,'wait_order_id','id');
    }

    public function Extra()
    {
        return $this->hasMany(Extra_wait_order_m::class,'wait_order_id','id');
    }

}
