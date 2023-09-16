<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class stockOrders extends Model
{
    use HasFactory , SoftDeletes;
    protected $table = 'stock_orders';
    protected $guarded = [];
    protected $dates = ['deleted_at'];
    public function details(){
        return $this->hasMany(stockOrdersDetails::class,'order_id','id');
    }
}
