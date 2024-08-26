<?php

namespace App\Movements\Interface;

use App\Models\Stock\Store;
use App\Models\Stock\Section;
use App\Models\Stock\Purchases;
use App\Models\Stock\PurchasesDetails;
use  Illuminate\Database\Eloquent\Collection;

interface MovementInterface
{

    /**
     * create
     * @param Section|Store $model
     * @return bool
     */
    public function create(
        Section|Store $model,
    ): bool;

    /** 
     * delete
     * @param Purchases $purchases
     * @param PurchasesDetails $details
     * @return bool
     */
    public function deletePurchaseMovement(
        Purchases $purchases,
        int $id,
    ): bool;
}
