<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class exchangeMain extends Model
{
    use HasFactory;
    protected  $table ='exchange_mains';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];

    public function details(){
        return $this->hasMany(exchangeDetails::class,'order_id','id');
    }

    public function section(){
        return $this->belongsTo(stocksection::class,'section_id','id');
    }
}

