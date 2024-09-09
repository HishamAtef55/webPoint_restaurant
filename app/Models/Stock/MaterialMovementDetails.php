<?php

namespace App\Models\Stock;

use App\Models\Stock\Exchange;
use App\Models\Stock\Material;
use App\Models\Stock\Purchases;
use App\Models\Stock\MaterialTransfer;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

class MaterialMovementDetails extends MorphPivot
{
    use HasFactory;

    protected $table = 'stock_materials_movement_details';

    /**
     * @var string[]
     */
    protected $fillable = ['stockable_id', 'stockable_type', 'material_id', 'expire_date', 'qty', 'price', 'discount', 'total'];

    /**
     * Get the owning stockable model (Purchases, Exchange, or MaterialTransfer).
     *
     * @return MorphTo
     */
    public function stockable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * purchases
     *
     * @return MorphTo
     */
    public function purchases(): MorphTo
    {
        return $this->morphTo(Purchases::class, 'stockable');
    }

    /**
     * exchange
     *
     * @return MorphTo
     */
    public function exchange(): MorphTo
    {
        return $this->morphTo(Exchange::class, 'stockable');
    }

    /**
     * transfer
     *
     * @return MorphTo
     */
    public function transfer(): MorphTo
    {
        return $this->morphTo(MaterialTransfer::class, 'stockable');
    }

    /**
     * halk
     *
     * @return MorphTo
     */
    public function halk(): MorphTo
    {
        return $this->morphTo(MaterialTransfer::class, 'stockable');
    }

    /**
     * supplier_refund
     *
     * @return MorphTo
     */
    public function supplier_refund(): MorphTo
    {
        return $this->morphTo(MaterialSupplierRefund::class, 'stockable');
    }


    /**
     * materials
     *
     * @return BelongsTo
     */
    public function materials(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id', 'id');
    }
}
