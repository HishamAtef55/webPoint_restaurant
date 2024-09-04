<?php

namespace App\Models\Stock;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Stock\MaterialTransferDetails;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialTransfer extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = "stock_materials_transfer";

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

    /**
     * user
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * from_section
     *
     * @return BelongsTo
     */
    public function from_section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'from_section_id', 'id');
    }

    /**
     * to_section
     *
     * @return BelongsTo
     */
    public function to_section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'to_section_id', 'id');
    }

        /**
     * from_store
     *
     * @return BelongsTo
     */
    public function from_store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'from_store_id', 'id');
    }

    /**
     * to_store
     *
     * @return BelongsTo
     */
    public function to_store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'to_store_id', 'id');
    }

    /**
     * details
     *
     * @return HasMany
     */
    public function details(): HasMany
    {
        return $this->hasMany(MaterialTransferDetails::class, 'transfer_id', 'id');
    }

    /**
     * details
     *
     * @return bool
     */
    public function hasDetails(): bool
    {
        return $this->details->count() > 1;
    }
}
