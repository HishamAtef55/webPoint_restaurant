<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class backToStoreMain extends Model
{
    use HasFactory;
    protected  $table ='stock_back_to_store_mains';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function details(){
        return $this->hasMany(backToStoreDetails::class,'order_id','id');
    }
}
