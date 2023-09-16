<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class storePurchasesDetails extends Model
{
    use HasFactory;
    protected  $table ='store_purchases_details';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function main(){
        return $this->belongsTo(storePurchases::class,'order_id','id');
    }
}
