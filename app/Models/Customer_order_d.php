<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
class Customer_order_d extends Model
{
    protected $guarded = [];
    public function Customer()
    {
        return $this->hasMany(Customer::class,'id','customer_id');
    }
}
