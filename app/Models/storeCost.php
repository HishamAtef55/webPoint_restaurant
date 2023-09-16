<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class storeCost extends Model
{
    use HasFactory;
    protected  $table ='store_costs';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function store(){
        return $this->belongsTo(Stores::class,'store_id','id');
    }
    public function MainMaterial(){
        return $this->belongsTo(material::class , 'code','code');
    }
    public function sub_unit(){
        return $this->belongsTo('App\Models\Units','unit','name')->select(['id','name','size']);
    }
}
