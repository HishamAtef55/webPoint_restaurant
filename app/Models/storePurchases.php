<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class storePurchases extends Model
{
    use HasFactory;
    protected  $table ='store_purchases';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function store(){
        return $this->belongsTo(Stores::class,'store_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user','id');
    }
    public function supplier(){
        return $this->belongsTo(Suppliers::class,'supplier','id');
    }
    public function details(){
        return $this->hasMany(storePurchasesDetails::class,'order_id','id');
    }
}

