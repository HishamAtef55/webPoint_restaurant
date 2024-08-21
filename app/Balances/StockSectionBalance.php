<?php

namespace App\Balances;

use App\Models\Stock\Purchases;

use App\Models\Stock\StoreBalance;
use App\Balances\Abstract\BalanceAbstract;
use App\Balances\Interface\BalanceInterface;
use App\Balances\Service\ConcreteBalanceService;

class StockSectionBalance extends BalanceAbstract implements BalanceInterface
{

    protected $model = StoreBalance::class;
    /**
     * create
     *
     * @return StoreBalance
     */
    public function create(): StoreBalance
    {
        return new StoreBalance();
    }

    /**
     * update
     *
     * @return void
     */
    public function update(): void
    {
        // TODO: Implement update() method.
    }

    /**
     * delete
     *
     * @return void
     */
    public function delete(): void
    {
        // TODO: Implement delete() method.
    }
}
