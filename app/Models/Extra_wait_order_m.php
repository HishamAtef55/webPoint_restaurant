<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Wait_order_m;
use App\Models\extra;
class Extra_wait_order_m extends Model
{
    protected $table = 'extra_wait_orders_m';
    protected $fillable = [
        'id',
        'number_of_order',
        'extra_id',
        'price',
        'name',
        'wait_order_id',
        'sub_num_order'
    ];
    public function Wait_Order()
    {
        return $this->belongsTo(Wait_order_m::class,'wait_order_id','id');
    }
    public function mainextra(){
        return $this->belongsTo(extra::class , 'extra_id','id');
    }
}
