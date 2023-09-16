<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer_phone;
use App\Models\Orders_d;
class Customer extends Model
{
    protected $table = 'customers';
    protected $guarded = [];
    protected $hidden = [
        'created_at',
        'uploaded_at',
    ];
    
    public function Phones()
    {
        return $this->hasMany(Customer_phone::class,'customer_id','id');
    }
    public function Orders()
    {
        return $this->hasMany(Orders_d::class,'customer_id','id');
    }

//    public function Wait_orders()
//    {
//        return $this->belongsToMany('App\Models\Wait_order','customer_id','wait_order_id','customer_wait_order')
//            ->withPivot('branch_id','customer_id','wait_order_id','t_order','d_order','delivery_order','pilot','hold','hold_list','date_holde_list');
//    }
//    public function wait_order()
//    {
//        return $this->belongsToMany('App\Models\customer_wait_order','wait_orders','table_id','customer_id')
//            ->withPivot('branch_id','customer_id','wait_order_id','t_order','d_order','delivery_order','pilot','hold','hold_list','date_holde_list');
//    }
}
