<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\discounts;
use App\Models\Item_Details;
use App\Models\menu;

class Branch extends Model
{
    protected $table = 'branchs';
    protected $fillable = ['name'];
    protected $hidden = ['created_at', 'updated_at'];

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

    /**
     * Groups
     *
     * @return HasMany
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'branch_id', 'id');
    }
}
