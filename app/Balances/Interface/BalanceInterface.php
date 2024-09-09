<?php

namespace App\Balances\Interface;

use App\Models\Stock\Store;
use App\Models\Stock\Section;
use App\Models\Stock\Material;


interface BalanceInterface
{

    /**
     * purchasesBalance
     * @param Store|Section $model
     * @return bool
     */
    public function purchasesBalance(
        mixed $model
    ): bool;

    /**
     * increaseBalance
     * @param Store|Section $model
     * @return bool
     */

    public function increaseBalance(
        mixed $model
    ): bool;


    /**
     * decreaseBalance
     * @param Store|Section $model
     * @return bool
     */

    public function decreaseBalance(
        mixed $model
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
