<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Table;
use App\Models\Orders_m;
use App\Models\Details_Wait_Order_m;
use App\Models\Extra_wait_order_m;
use App\Models\ItemPrinters;
use App\Models\Item;
class Wait_order_m extends Model
{
    protected  $table ='wait_orders_m';
    protected $guarded = [];

    public function Tables()
    {
        return $this->belongsTo(Table::class,'table_id','id');
    }

    public function Order()
    {
        return $this->belongsTo(Orders_m::class,'order_id','order_id');
    }

    public function Details()
    {
        return $this->hasMany(Details_Wait_Order_m::class,'wait_order_id','id');
    }

    public function Extra()
    {
        return $this->hasMany(Extra_wait_order_m::class,'wait_order_id','id');
    }

    public function Printer()
    {
        return $this->hasMany(ItemPrinters::class,'item_id','item_id');
    }
    public function item(){
        return $this->belongsTo(Item::class , 'item_id','id');
    }
}
