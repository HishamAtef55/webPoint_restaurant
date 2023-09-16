<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DetailsWaitOrdersDel;
use App\Models\ExtraWaitOrdersDel;

class WaitOrdersDel extends Model
{
    protected $table = 'wait_orders_dels';
    protected $guarded = [];
    public function Details()
    {
        return $this->hasMany(DetailsWaitOrdersDel::class,'wait_order_id','id');
    }
    public function Extra()
    {
        return $this->hasMany(ExtraWaitOrdersDel::class,'wait_order_id','id');
    }
}
