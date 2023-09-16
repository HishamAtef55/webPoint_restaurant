<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class manufacturingMain extends Model
{
    use HasFactory;
    protected $table = 'manufacturing_mains';
    protected $guarded = [];
    public function details(){
        return $this->hasMany(manufacturingDetails::class,'order_id','id');
    }
}
