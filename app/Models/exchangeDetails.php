<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class exchangeDetails extends Model
{
    use HasFactory;
    protected  $table ='stock_exchange_details';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function main(){
        return $this->belongsTo(exchangeMain::class,'order_id','id');
    }
}
