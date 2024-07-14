<?php

namespace App\Models\Traits;

use App\Models\Stock\StockGroup;


trait HasSerialNumber
{

    public const INTIAL_MAIN_STOCK_GROUP_NR = '01';
    public const INTIAL_SUB_STOCK_GROUP_NR = '001';
    
    /**
     * booted
     *
     * @return void
     */
    protected static function booted(): void
    {
        parent::boot();

        static::creating(function (StockGroup $stockGroup) {
            $stockGroup->setSerialNr();
        });

        static::updating(function (StockGroup $stockGroup) {
            $stockGroup->setSerialNr();
        });
    }

    /**
     * setSerialNr
     *
     * @return void
     */
    private function setSerialNr()
    {
        if (!$this->parent_id) {
            $this->serial_nr = static::generateMainGroupStartSerial();
        } else {
            $this->serial_nr = static::generateSubGroupStartSerial($this->parent_id);
        }
    }

    /**
     * generateMainGroupStartSerial
     *
     * @return void
     */
    private static function generateMainGroupStartSerial()
    {
        $mainGroup = StockGroup::isRoot()->latest('serial_nr')->first();
        if ($mainGroup) {
            $nextSerialNr = $mainGroup->serial_nr + 1;  // 02
        } else {
            $nextSerialNr = static::INTIAL_MAIN_STOCK_GROUP_NR; // 01
        }
        return str_pad($nextSerialNr, strlen(self::INTIAL_MAIN_STOCK_GROUP_NR), '0', STR_PAD_LEFT);
    }

    /**
     * generateSubGroupStartSerial
     *
     * @param  mixed $parentId
     * @return void
     */
    private static function generateSubGroupStartSerial($parentId)
    {
        $parentGroup = StockGroup::find($parentId); 
        $subGroup = $parentGroup->children()->latest('serial_nr')->first();
        if ($subGroup) {
            $stockGroupSerialNr = $subGroup->serial_nr;
            $lastThreeDigits = substr($stockGroupSerialNr, -3); //01001
            $nextSerialNr = (int)$lastThreeDigits + 1; // 002
        } else {
            $nextSerialNr = static::INTIAL_SUB_STOCK_GROUP_NR; // 001
        }
        return $parentGroup->serial_nr . str_pad($nextSerialNr, strlen(static::INTIAL_SUB_STOCK_GROUP_NR), '0', STR_PAD_LEFT);
    }
}
