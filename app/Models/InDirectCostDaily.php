<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InDirectCostDaily extends Model
{
    use HasFactory;
    protected $table = 'stock_in_direct_cost_dailies';
    protected $guarded = [];
    public function cost(){
        return $this->belongsTo(InDirectCost::class,'cost_id','id');
    }
}
