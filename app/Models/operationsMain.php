<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class operationsMain extends Model
{
    use HasFactory;
    protected $table = 'operations_mains';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function details(){
        return $this->hasMany(operationsDetails::class,'order_id','id');
    }
    public function materialCost(){
        return $this->hasMany(sectionCost::class,'code','code');
    }
}
