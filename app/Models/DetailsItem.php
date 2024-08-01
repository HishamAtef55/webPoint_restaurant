<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailsItem extends Model
{
    use HasFactory;
    protected $table = 'details_items';
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
    public function details()
    {
        return $this->belongsTo(Details::class, 'detail_id', 'id');
    }
    public function materials()
    {
        return $this->hasMany(mainDetailsComponent::class, 'details', 'detail_id');
    }

    /**
     * details_material_components
     *
     * @return HasMany
     */

    public function details_material_components(): HasMany
    {
        return $this->hasMany(detailsComponent::class, 'details', 'detail_id');
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
}
