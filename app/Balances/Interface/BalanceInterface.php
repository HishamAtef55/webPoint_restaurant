<?php

namespace App\Balances\Interface;

use App\Models\Stock\StoreBalance;

interface BalanceInterface
{

    /**
     * create
     *
     * @return StoreBalance
     */
    public function create(): StoreBalance;

    /**
     * update
     *
     * @return void
     */
    public function update(): void;

    /**
     * delete
     *
     * @return void
     */
    public function delete(): void;
}
