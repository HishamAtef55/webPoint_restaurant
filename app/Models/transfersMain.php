<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transfersMain extends Model
{
    use HasFactory;
    protected  $table ='transfers_mains';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];

    // Relations
    public function details(){
        return $this->hasMany(transfersDetails::class,'order_id','id');
    }
    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
    public function to_section(){
        return $this->belongsTo(stocksection::class,'to','id');
    }
    public function from_section(){
        return $this->belongsTo(stocksection::class,'from','id');
    }
    public function to_store(){
        return $this->belongsTo(Stores::class,'to','id');
    }
    public function from_store(){
        return $this->belongsTo(Stores::class,'from','id');
    }
}
