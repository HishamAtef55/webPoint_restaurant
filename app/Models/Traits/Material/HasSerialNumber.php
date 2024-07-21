<?php

namespace App\Models\Traits\Material;

use App\Models\Stock\Material;
use App\Models\Stock\StockGroup;


trait HasSerialNumber
{

    public const INTIAL_MATERIAL_NR = '001';

    /**
     * booted
     *
     * @return void
     */
    protected static function booted(): void
    {
        parent::boot();

        static::creating(function (Material $material) {
            $material->serial_nr = self::generateMaterialSerialNr($material);
        });

        static::updating(function (Material $material) {
            $material->serial_nr = self::updateMaterialSerialNr($material);
        });
    }


    /**
     * generateMaterialSerialNr
     *
     * @return void
     */
    private static function generateMaterialSerialNr($material)
    {
        $stockGroup = StockGroup::find($material->group_id);
        $material = Material::where('group_id', $material->group_id)->latest()->first();
        if ($material) {
            $nextSerialNr = $material->serial_nr + 1;  // 02
        } else {
            $nextSerialNr = $stockGroup->serial_nr . static::INTIAL_MATERIAL_NR; // 01
        }
        return  $nextSerialNr;
    }

    /**
     * updateMaterialSerialNr
     *
     * @return void
     */
    private static function updateMaterialSerialNr($material)
    {
        $stockGroup = StockGroup::find($material->group_id);
        $material = Material::where('group_id', $material->group_id)->latest()->first();
        if ($material) {
            $nextSerialNr = $material->serial_nr + 1;  // 02
        } else {
            $nextSerialNr = $stockGroup->serial_nr . static::INTIAL_MATERIAL_NR; // 01
        }
        return  $nextSerialNr;
    }
}
