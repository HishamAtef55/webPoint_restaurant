<?php

namespace App\Balances\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @author Hisham Atef
 * @method static array validate()
 * @method static array create()
 *
 * @see \App\Balances\StockSectionBalance;
 * @see \App\Balances\StockStoreBalance;
 */
class Balance extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     */
    public static function getFacadeAccessor(): string
    {
        return 'balances';
    }
}
