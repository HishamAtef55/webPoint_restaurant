<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sectionPurchasesDetails extends Model
{
    use HasFactory;
    protected  $table ='stock_section_purchases_details';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function main(){
        return $this->belongsTo(sectionPurchases::class,'order_id','id');
    }
}
