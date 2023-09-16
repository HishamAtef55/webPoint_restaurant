<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transfersDetails extends Model
{
    use HasFactory;
    protected  $table ='transfers_details';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];

    public function main(){
        return $this->belongsTo(transfersMain::class,'order_id','id');
    }
}
