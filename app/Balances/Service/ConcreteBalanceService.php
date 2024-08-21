<?php

namespace App\Balances\Service;

use App\Balances\StockStoreBalance;
use App\Balances\StockSectionBalance;


class ConcreteBalanceService
{
    protected $storeBalance;
    protected $sectionBalance;

    public function __construct()
    {
        $this->storeBalance = new StockStoreBalance();
        $this->sectionBalance = new StockSectionBalance();
    }

    public function storeBalance()
    {
        return $this->storeBalance;
    }

    public function sectionBalance()
    {
        return $this->sectionBalance;
    }
}
