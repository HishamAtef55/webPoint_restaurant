<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Wait_order;

class Details_Wait_Order extends Model
{
    protected $table = 'details_wait_orders';
    protected $fillable = [
        'id',
        'number_of_order',
        'detail_id',
        'price',
        'name',
        'wait_order_id',
        'sub_num_order',
        'item_id'
    ];

    public function Wait_Order()
    {
        return $this->belongsTo(Wait_order::class,'wait_order_id','id');
    }
}
