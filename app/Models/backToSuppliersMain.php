<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class backToSuppliersMain extends Model
{
    use HasFactory;
    protected  $table ='back_to_suppliers_mains';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function details(){
        return $this->hasMany(backToSuppliersDetails::class,'order_id','id');
    }
}
