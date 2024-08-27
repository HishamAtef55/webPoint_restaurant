<?php

namespace App\Models\Stock;

use App\Models\Stock\Material;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeDetails extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = "stock_exchange_details";

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
    protected $guarded = [];

    public function exchange()
    {
        return $this->belongsTo(Exchange::class, 'exchange_id', 'id');
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
