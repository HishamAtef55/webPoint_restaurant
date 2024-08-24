<?php

namespace App\Balances\Interface;

use App\Models\Stock\Store;
use App\Models\Stock\Section;
use App\Models\Stock\Purchases;
use App\Models\Stock\StoreBalance;
use  Illuminate\Database\Eloquent\Collection;

interface BalanceInterface
{

    /**
     * create
     * @param mixed $model
     * @return bool
     */
    public function create(
        mixed $model
    ): bool;

    /**
     * update
     * @param Section|Store $model
     * @return bool
     */
    public function update(
        mixed $model
    ): bool;

    /**
     * delete
     * @param Purchases $purchases
     * @param int $id
     * @return bool
     */
    public function delete(
        Purchases $purchases,
        int $id
    ): bool;
}
