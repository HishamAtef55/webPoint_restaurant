<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Wait_order_m;
class Details_Wait_Order_m extends Model
{
    protected $table = 'details_wait_orders_m';
    protected $guarded = [];

    public function Wait_Order()
    {
        return $this->belongsTo(Wait_order_m::class,'wait_order_id','id');
    }
}
