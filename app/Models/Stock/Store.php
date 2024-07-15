<?php

namespace App\Models\Stock;

use App\Models\Stock\StorageCapacity;
use App\Models\StockSection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{

    protected  $table = 'stock_stores';

    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'phone', 'address'
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
    protected $casts = [];

    /**
     * storageCapacity
     *
     * @return HasMany
     */
    public function storageCapacity(): HasMany
    {
        return $this->hasMany(StorageCapacity::class, 'store_id', 'id');
    }

    /**
     * storageCapacity
     *
     * @return HasMany
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'store_id', 'id');
    }

    public function hasSection(): bool
    {
        return (bool) $this->sections()->count();
    }
}
