<?php

namespace App\Models;

use App\Models\menu;
use App\Models\Sub_group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    protected $table = 'groups';
    protected $fillable = ['name', 'menu_id', 'branch_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function Menu()
    {
        return $this->belongsTo(menu::class, 'menu_id', 'id');
    }

    // public function Branch()
    // {
    //     return $this->hasManyThrough(group::class, menu::class, 'branch_id', 'menu_id', 'id', 'id');
    // }

    public function Supgroups()
    {
        return $this->hasMany(Sub_group::class, 'group_id', 'id');
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
     * sections
     *
     * @return BelongsToMany
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(StockSection::class, 'stock_section_groups', 'section_id', 'group_id');
    }
}
