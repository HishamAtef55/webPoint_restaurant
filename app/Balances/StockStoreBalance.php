<?php

namespace App\Balances;


use App\Models\Stock\Store;
use App\Models\Stock\Material;
use App\Models\Stock\Purchases;
use Illuminate\Support\Facades\DB;
use App\Movements\Facades\Movement;
use Illuminate\Support\Facades\Log;
use App\Balances\Abstract\BalanceAbstract;
use App\Balances\Interface\BalanceInterface;

class StockStoreBalance extends BalanceAbstract implements BalanceInterface
{

    protected Store $model;

    /**
     * create
     * @param Store $store
     * @return bool
     */
    public function purchasesBalance(
        $store
    ): bool {
        try {

            if (!$this->balance) return false;
            /*
            * increase balance of section
            */
            foreach ($this->balance as $balance) {
                $oldBalance = $store->balance()->where('material_id', $balance['material_id'])->first();
                if ($oldBalance) {
                    $price = ($oldBalance->avg_price + $balance['price']) /  ($oldBalance->qty  + $balance['qty']);
                    $qty = $oldBalance->qty += $balance['qty'];
                    $oldBalance->update([
                        'qty' => $qty,
                        'avg_price' => $price,
                    ]);
                } else {
                    $store->balance()->create(
                        [
                            'store_id' => $store->id,
                            'material_id' => $balance['material_id'],
                            'qty' =>  $balance['qty'],
                            'avg_price' => $balance['price'],
                        ]
                    );
                }
            }


            return true;
        } catch (\Throwable $e) {
            Log::error('increase store balance creation failed: ' . $e->getMessage(), [
                'balance' => $this->balance,
                'params' => $store,
            ]);
            return false;
        }
    }

    /**
     * currentBalance
     * @param Material $material
     * @param int $id
     * @return int
     */
    public function currentBalance(
        Material $material,
        int $id
    ): int {
        $store = Store::findOrFail($id);
        $balance = $store->balance()->whereBelongsTo($material)->first()->qty ?? 0;
        return $balance;
    }
}
