<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialRecipe extends Model
{
    use HasFactory;


    /**
     * table
     *
     * @var string
     */
    protected $table = "stock_material_recipes";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['material_id', 'material_recipe_id', 'quantity', 'price', 'unit'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     * guarded
     *
     * @var array<string, string>
     */
    protected $guarded = [];


    /**
     * groups
     *
     * @return BelongsTo
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_recipe_id', 'id');
    }

    /**
     * Scope a query to filter by material_id if provided.
     *
     * @param Builder $builder
     * @param mixed $materialId
     * @return Builder
     */
    public function scopeByMaterialId(Builder $builder, $materialId = null)
    {
        return $builder->when($materialId, function ($query, $materialId) {
            return $query->where('material_id',$materialId);
        });
    }
}
