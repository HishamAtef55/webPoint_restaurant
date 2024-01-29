<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class halkMain extends Model
{
    use HasFactory;
    protected  $table ='stock_halk_mains';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function details(){
        return $this->hasMany(halkDetails::class,'order_id','id');
    }
}
