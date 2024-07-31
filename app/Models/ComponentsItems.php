<?php

namespace App\Models;

use App\Models\Stock\Material;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComponentsItems extends Model
{
    use HasFactory;
    protected $table = 'stock_components_items';
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
    public function material_with_packing()
    {
        return $this->belongsTo(Material::class, 'material_id', 'code');
    }
}
