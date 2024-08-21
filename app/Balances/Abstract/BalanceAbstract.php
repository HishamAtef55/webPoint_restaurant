<?php

namespace App\Balances\Abstract;

use App\Models\Stock\Purchases;

abstract class BalanceAbstract
{
    /**
     * create
     * @param Purchases $purchases
     * @return self
     */
    public function validate(
        Purchases $purchase
    ): self {
        return $this;
    }
}
