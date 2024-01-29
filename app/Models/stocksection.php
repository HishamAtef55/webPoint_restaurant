<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stocksection extends Model
{
    use HasFactory;
    protected  $table ='stock_ections';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];

    public function sectiongroup(){
        return $this->hasMany(section_group::class,'section_id','id');
    }
    public function sectionstore(){
        return $this->hasOne(section_store::class,'section_id','id');
    }
    public function sectionsBranch(){
        return $this->hasOne(Branch::class,'id','branch');
    }
}
