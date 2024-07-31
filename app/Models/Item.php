<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sub_group;
use App\Models\Details;
use App\Models\extra;
use App\Models\BarcodeItems;
use App\Models\ItemPrinters;
use App\Models\Stock\Material;
use App\Models\Wait_order;
use App\Models\Wait_order_m;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $table = 'items';
    protected $guarded = [];

    public function Sub_group()
    {
        return $this->belongsTo(Sub_group::class, 'sub_group_id', 'id');
    }

    public function Details()
    {
        return $this->belongsToMany(Details::class, 'details_items', 'item_id', 'detail_id')
            ->withPivot(['price', 'section', 'max']);
    }

    public function Extra()
    {
        return $this->belongsToMany(extra::class, 'extra_item', 'item_id', 'extra_id')
            ->withPivot(['price']);
    }

    public function Barcode()
    {
        return $this->hasMany(BarcodeItems::class, 'item', 'id');
    }

    public function Printer()
    {
        return $this->hasMany(ItemPrinters::class, 'item_id', 'id');
    }

    public function Wait_Order()
    {
        return $this->belongsToMany(Wait_order::class, 'extra_order', 'item_id', 'Order_Number_dev');
    }

    public function WaitOrderM()
    {
        return $this->hasMany(Wait_order_m::class, 'item_id', 'id');
    }

    public function Materials()
    {
        return $this->belongsToMany(Material::class, 'components_items', 'item_id', 'id');
    }

    public function material_components()
    {
        return $this->hasMany(ComponentsItems::class, 'item_id', 'id');
    }

    public function material_direct()
    {
        return $this->hasMany(ComponentsItems::class, 'item_id', 'id')->whereHas('material_with_packing', function ($query) {
            $query->where('packing', 0);
        });
    }

    public function material_indirect()
    {
        return $this->hasMany(ComponentsItems::class, 'item_id', 'id')->whereHas('material_with_packing', function ($query) {
            $query->where('packing', '1');
        });
    }

    public function getdetails()
    {
        return $this->hasMany(DetailsItem::class, 'item_id', 'id');
    }

    public function details_components()
    {
        return $this->hasMany(mainDetailsComponent::class, 'item', 'id');
    }

    public function custom_materials()
    {
        return $this->belongsTo(MainComponents::class, 'id', 'item');
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
