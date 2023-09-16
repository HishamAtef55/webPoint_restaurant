<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Units extends Model
{
    use HasFactory;
    protected $table = 'stock_units';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function sub_unit(){
        return $this->hasOne(SubUnit::class , 'unit_id','id');
    }
}
