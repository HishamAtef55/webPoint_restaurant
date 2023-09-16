<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Details_Wait_Order_m;
use App\Models\Extra_wait_order_m;
class Void_d extends Model
{
    protected  $table ='void';
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
