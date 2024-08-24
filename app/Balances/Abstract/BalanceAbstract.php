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

    /*
     * The attributes that hold material move.
     *
     * @var array<int, string>
    */
    protected $move = [];

    /**
     * create
     * @param Purchases $purchases
     * @return self
     */
    public function validate(
        Purchases $purchase
    ): self {
        $purchase->details->map(function ($item) use ($purchase) {
            return [
                array_push($this->move, [
                    'invoice_nr' => $purchase->serial_nr,
                    'material_id' => $item->material_id,
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'type' => MaterialMove::PURCHASES->value
                ])
            ];
        });
        return $this;
    }
}
