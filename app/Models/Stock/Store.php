<?php

namespace App\Models\Stock;

use App\Models\StockSection;
use App\Models\Stock\StorageCapacity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
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
        'name',
        'phone',
        'address'
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
     * sections
     *
     * @return HasMany
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'store_id', 'id');
    }

    /**
     * hasSection
     *
     * @return bool
     */

    public function hasSection(): bool
    {
        return (bool) $this->sections()->count();
    }

    /**
     * balance
     *
     * @return HasMany
     */

    public function balance(): HasMany
    {
        return $this->hasMany(StoreBalance::class, 'store_id', 'id');
    }

    /**
     * balance
     *
     * @return HasMany
     */

    public function move(): HasMany
    {
        return $this->hasMany(StoreMaterialMove::class, 'store_id', 'id');
    }

    /**
     * getBalance
     *
     * @return Collection
     */

    public function getMovement(): Collection
    {
        return $this->move()->get();
    }
}
