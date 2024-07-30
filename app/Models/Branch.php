<?php

namespace App\Models;

use App\Models\menu;
use App\Models\User;
use App\Models\discounts;
use App\Models\Item_Details;
use App\Models\Stock\Section;
use App\Models\Stock\Material;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{

    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected  $table = 'branchs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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
     * Groups
     *
     * @return HasMany
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'group_id', 'id');
    }

    /**
     * sections
     *
     * @return HasMany
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'branch_id', 'id');
    }

    /**
     * items
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'branch_id', 'id');
    }

    /**
     * materials
     *
     * @return HasMany
     */
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'branch_id', 'id');
    }

    /**
     * mainComponent
     *
     * @return BelongsTo
     */
    public function mainComponent(): BelongsTo
    {
        return $this->belongsTo(MainComponents::class, 'id', 'branch');
    }


    public function Menus()
    {
        return $this->hasMany(menu::class, 'branch_id', 'id');
    }

    public function Items_Detels()
    {
        return $this->hasMany(Item_Details::class, 'branch_id', 'id');
    }

    public function Discounts()
    {
        return $this->hasMany(discounts::class, 'branch_id', 'id');
    }

    public function Users()
    {
        return $this->hasMany(User::class, 'branch_id', 'id');
    }
}
