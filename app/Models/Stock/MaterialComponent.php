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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\Material\HasSerialNumber;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MaterialComponent extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = "stock_material_components";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'material_id','qty'
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
    ];

    /**
     * guarded
     *
     * @var array<string, string>
     */
    protected $guarded = [];


}
