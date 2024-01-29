<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InDirectCost extends Model
{
    use HasFactory;
    protected $table = 'stock_in_direct_costs';
    protected $guarded = [];
    public function cost(){
        return $this->hasMany(InDirectCost::class,'cost_id','id');
    }
}
