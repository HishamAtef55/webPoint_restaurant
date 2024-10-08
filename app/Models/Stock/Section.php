<?php

namespace App\Models\Stock;

use App\Models\Group;
use App\Models\Branch;
use App\Models\Stock\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
class Section extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected  $table = 'stock_sections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'branch_id',
        'store_id'
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
     * groups
     *
     * @return BelongsToMany
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'stock_section_groups', 'section_id', 'group_id');
    }

    /**
     * branch
     *
     * @return BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    /**
     * store
     *
     * @return BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    /**
     * balance
     *
     * @return HasMany
     */

    public function balance(): HasMany
    {
        return $this->hasMany(SectionBalance::class, 'section_id', 'id');
    }

    /**
     * move
     *
     * @return HasMany
     */

    public function move(): HasMany
    {
        return $this->hasMany(SectionMaterialMove::class, 'section_id', 'id');
    }

    /**
     * getBalance
     *
     * @return Collection
     */

    public function getBalance(): Collection
    {
        return $this->move()->get()->groupBy('material_id');
    }
}
