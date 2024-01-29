<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mainMaterialRecipe extends Model
{
    use HasFactory;
    protected $table = 'stock_main_material_recipes';
    protected $guarded = [];
    protected $hidden = ['created_at','updates_at'];
    public function materials(){
        return $this->hasMany(materialRecipe::class , 'main_id','id');
    }
}
