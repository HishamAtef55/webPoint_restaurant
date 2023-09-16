<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sectionPurchases extends Model
{
    use HasFactory;
    protected  $table ='section_purchases';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function section(){
        return $this->belongsTo(stocksection::class,'section_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user','id');
    }
    public function supplier(){
        return $this->belongsTo(Suppliers::class,'supplier','id');
    }
    public function details(){
        return $this->hasMany(sectionPurchasesDetails::class,'order_id','id');
    }
}
