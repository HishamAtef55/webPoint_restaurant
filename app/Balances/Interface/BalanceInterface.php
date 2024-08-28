<?php

namespace App\Balances\Interface;

use App\Models\Stock\Store;
use App\Models\Stock\Section;
use App\Models\Stock\Material;


interface BalanceInterface
{

    /**
     * create
     * @param Store|Section $model
     * @return bool
     */
    public function purchasesBalance(
        mixed $model
    ): bool;

    /**
     * exchangeBalance
     * @param Store $store
     * @return bool
     */
    public function exchangeBalance(
        $store
    ): bool;

    /**
     * create
     * @param Material $material
     * @param int $id
     * @return int
     */
    public function currentBalanceByMaterial(
        Material $material,
        int $id
    ): int;
}
