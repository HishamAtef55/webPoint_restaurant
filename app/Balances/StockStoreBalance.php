<?php

namespace App\Balances;

use App\Models\Stock\Purchases;

use App\Models\Stock\StoreBalance;
use App\Balances\Abstract\BalanceAbstract;
use App\Balances\Interface\BalanceInterface;
use App\Balances\Service\ConcreteBalanceService;

class StockStoreBalance extends BalanceAbstract implements BalanceInterface
{

    protected $model = StoreBalance::class;

    /**
     * create
     *
     * @return StoreBalance
     */
    public function create(): StoreBalance
    {
        return  $this->model::create([
            'store_id' => 108,
            'material_id' => 26,
            'qty' => 10,
            'price' => 5000,
        ]);
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
