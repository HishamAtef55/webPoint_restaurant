<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComponentsItems extends Model
{
    use HasFactory;
    protected $table = 'components_items';
    protected $guarded =[];
    protected $hidden = ['created_at','updated_at'];
    public function item(){
        return $this->belongsTo(Items::class,'item_id','id');
    }
    public function material_with_packing(){
        return $this->belongsTo(material::class,'material_id','code');
    }
}
