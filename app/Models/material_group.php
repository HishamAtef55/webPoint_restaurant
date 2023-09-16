<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class material_group extends Model
{
    use HasFactory;
    protected  $table ='material_groups';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function maingroup(){
        return $this->hasOne(MainGroup::class,'id','main_group');
    }
}
