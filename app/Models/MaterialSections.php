<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialSections extends Model
{
    use HasFactory;
    protected $table = "stock_material_sections";
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function material(){
        return $this->belongsTo(material::class,'material','code');
    }
}
