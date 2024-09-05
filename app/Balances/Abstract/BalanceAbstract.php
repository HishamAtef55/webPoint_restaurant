<?php

namespace App\Balances\Abstract;

use App\Enums\MaterialMove;
use App\Enums\MaterialType;
use App\Models\Stock\Material;
use App\Models\Stock\Purchases;

abstract class BalanceAbstract
{
    /*
     * The attributes that hold balance.
     *
     * @var array<int, string>
    */
    protected $balance = [];

    /**
     * Reset balance property
     */
    protected function resetBalance(): void
    {
        $this->balance = [];
    }

    /**
     * validate
     * @param array $data
     * @return self
     */
    public function validate(
        array $data
    ): self {

        $this->resetBalance();

        array_map(function ($balance) {
            return array_push($this->balance, [
                'material_id' => $balance['material_id'],
                'qty' => $balance['qty'],
                'price' => $balance['price'],
            ]);
        }, $data);
        return $this;
    }
}
