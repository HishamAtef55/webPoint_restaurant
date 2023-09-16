<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class material extends Model
{
    use HasFactory;
    protected $table = "materials";
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function Sections()
    {
        return $this->hasMany('App\Models\MaterialSections','material','code');
    }
    public function sub_unit(){
        return $this->belongsTo('App\Models\Units','unit','name')->select(['id','name','size']);
    }

    public function group(){
        return $this->belongsTo(material_group::class,'sub_group','id');
    }
    public function components(){
        return $this->hasMany(ComponentsItems::class , 'material_id','code');
    }
    public function materialRecipe(){
        return $this->belongsTo(mainMaterialRecipe::class,'code','material');
    }
//    public function material_section(){
//        return $this->belongsTo()
//    }

}
