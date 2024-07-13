<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class StockGroup extends Model
{
    public const INTIAL_MAIN_STOCK_GROUP_NR = '01';
    public const INTIAL_SUB_STOCK_GROUP_NR = '001';

    use HasFactory, HasRecursiveRelationships;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'stock_groups';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'parent_id', 'start_serial', 'end_serial'
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
     * booted
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function (StockGroup $stockGroup) {
            if (!$stockGroup->parent_id) {
                $stockGroup->start_serial = static::generateMainGroupStartSerial();
                $stockGroup->end_serial = $stockGroup->start_serial;
            } else {
                $stockGroup->start_serial = static::generateSubGroupStartSerial($stockGroup->parent_id);
                $stockGroup->end_serial = $stockGroup->start_serial;
            }
        });
    }

    private static function generateMainGroupStartSerial()
    {
        $mainGroup = StockGroup::isRoot()->latest('start_serial')->first();
        if ($mainGroup) {
            $nextSerialNr = $mainGroup->start_serial + 1;
        } else {
            $nextSerialNr = static::INTIAL_MAIN_STOCK_GROUP_NR;
        }
        return str_pad($nextSerialNr, strlen(self::INTIAL_MAIN_STOCK_GROUP_NR), '0', STR_PAD_LEFT);
    }

    private static function generateSubGroupStartSerial($parentId)
    {
        $parentGroup = StockGroup::find($parentId);
        $subGroup = $parentGroup->children()->latest('start_serial')->first();
        if ($subGroup) {
        } else {
            $nextSerialNr = static::INTIAL_SUB_STOCK_GROUP_NR;
        }
        return $parentGroup->start_serial . str_pad($nextSerialNr, strlen(static::INTIAL_SUB_STOCK_GROUP_NR), '0', STR_PAD_LEFT);
    }
}
