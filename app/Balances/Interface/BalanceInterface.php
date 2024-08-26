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
     * create
     * @param Material $material
     * @param int $id
     * @return int
     */
    public function currentBalance(
        Material $material,
        int $id
    ): int;
}
