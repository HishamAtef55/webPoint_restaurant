<?php

namespace App\Models\Stock;

use App\Models\Stock\Material;
use App\Models\Stock\MaterialTransfer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialTransferDetails extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = "stock_materials_transfer_details";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

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
     * guarded
     *
     * @var array<string, string>
     */
    /**
     * guarded
     *
     * @var array<string, string>
     */
    protected $guarded = [];


    public function transfer()
    {
        return $this->belongsTo(MaterialTransfer::class, 'transfer_id ', 'id');
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
