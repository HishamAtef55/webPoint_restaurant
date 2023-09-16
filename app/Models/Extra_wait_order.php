<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\extra;
use App\Models\Wait_order;
class Extra_wait_order extends Model
{
    protected $table = 'extra_wait_orders';
    protected $fillable = [
        'id',
        'number_of_order',
        'extra_id',
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
    public function mainextra(){
        return $this->belongsTo(extra::class , 'extra_id','id');
    }
}
