<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sectionCost extends Model
{
    use HasFactory;
    protected  $table ='section_costs';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function section(){
        return $this->belongsTo(stocksection::class,'section_id','id');
    }
    public function sub_unit(){
        return $this->belongsTo('App\Models\Units','unit','name')->select(['id','name','size']);
    }
    public function MainMaterial(){
        return $this->belongsTo(material::class , 'code','code');
    }
}
