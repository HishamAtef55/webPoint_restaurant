<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class materialRecipe extends Model
{
    use HasFactory;
    protected $table = 'material_recipes';
    protected $guarded = [];
    protected $hidden = ['created_at','updates_at'];
}
