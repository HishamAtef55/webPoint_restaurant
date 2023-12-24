<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Table;
use App\Models\Details_Wait_Order;
use App\Models\Extra_wait_order;
use App\Models\ItemPrinters;
use App\Models\WithoutMaterialsD;

class Wait_order extends Model
{
    protected  $table ='wait_orders';
    protected $guarded = [];

    public function Tables()
    {
        return $this->belongsTo(Table::class,'table_id','id');
    }

    public function Details()
    {
        return $this->hasMany(Details_Wait_Order::class,'wait_order_id','id');
    }

    public function Extra()
    {
        return $this->hasMany(Extra_wait_order::class,'wait_order_id','id');
    }

    public function Printer()
    {
        return $this->hasMany(ItemPrinters::class,'item_id','item_id');
    }
    public function Without_m()
    {
        return $this->hasMany(WithoutMaterialsD::class,'wait_order_id','id');
    }
    
}
