<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Locations;
use App\Models\Wait_order;
use App\Models\Shift;
use App\Models\User;


class Orders_m extends Model
{
    protected $table ='orders_m';
    protected $guarded = [];

    public function Customer()
    {
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
    public function Locations()
    {
        return $this->belongsTo(Locations::class,'location','id');
    }
    public function WaitOrders()
    {
        return $this->hasMany(Wait_order_m::class,'order_id','order_id');
    }
    public function Shift()
    {
        return $this->belongsTo(Shift::class,'shift','id');
    }
    public function ShiftOpen()
    {
        return $this->belongsTo(Shift::class,'shift','id');
    }
    public function Cashier()
    {
      return $this->belongsTo(User::class,'cashier','id');
    }

}
