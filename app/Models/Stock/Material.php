<?php

namespace App\Models\Stock;

use App\Enums\Unit;
use App\Models\Item;
use App\Models\Branch;
use App\Casts\UnitCast;
use App\Casts\StorageCast;
use App\Enums\StorageType;
use App\Casts\MaterialCast;
use App\Enums\MaterialType;
use App\Models\ComponentsItems;
use App\Models\PurchasesDetails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\Material\HasSerialNumber;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Material extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = "stock_materials";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'serial_nr',
        'cost',
        'price',
        'unit',
        'loss_ratio',
        'min_store',
        'max_store',
        'min_section',
        'max_section',
        'storage_type',
        'material_type',
        'expire_date',
        'group_id',
        'branch_id',
    ];

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
    protected $casts = [
        // 'unit' => UnitCast::class,
        // 'storage_type' => StorageCast::class,
        // 'material_type' => MaterialCast::class,
    ];

    /**
     * guarded
     *
     * @var array<string, string>
     */
    protected $guarded = [];


    /**
     * groups
     *
     * @return BelongsToMany
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'stock_material_sections', 'material_id', 'section_id',);
    }

    /**
     * groups
     *
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(StockGroup::class, 'group_id', 'id');
    }

    /**
     * groups
     *
     * @return BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }


    /**
     * IsManufactured
     *
     * @param  mixed $builder
     * @return void
     */
    public function scopeIsManufactured(Builder $builder)
    {
        return $builder->where('material_type', MaterialType::MANUFACTURED_MATERIAL);
    }

    /**
     * recipes
     *
     * @return HasMany
     */

    public function recipes(): HasMany
    {
        return $this->hasMany(MaterialRecipe::class, 'material_id', 'id');
    }

    /**
     * component
     *
     * @return HasOne
     */

    public function component(): HasOne
    {
        return $this->hasOne(MaterialComponent::class, 'material_id', 'id');
    }

    /**
     * Calculate the total quantity of the material across all variants.
     *
     * @return int
     */
    public function getTotalQuantityAttribute()
    {
        return $this->recipes()->sum('quantity');
    }

    /**
     * Calculate the total cost of the material across all variants.
     *
     * @return float
     */
    public function getTotalPriceAttribute()
    {
        return $this->recipes()->sum('price');
    }

    /**
     * items
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(ComponentsItems::class, 'material_id', 'id');
    }

    /*
     * items
     * @return HasMany
    */
    public function details(): HasMany
    {
        return $this->hasMany(PurchasesDetails::class, 'material_id', 'id');
    }


    /**
     * hasDetails
     *
     * @return bool
     */

    public function hasDetails(): bool
    {
        return (bool) $this->details()->count();
    }

    /**
     * storageCapacity
     *
     * @return bool
     */

    public function hasRecipe(): bool
    {
        return (bool) $this->recipes()->count();
    }
}
