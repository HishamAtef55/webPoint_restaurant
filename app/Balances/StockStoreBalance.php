<?php

namespace App\Balances;


use App\Models\Stock\Store;
use App\Models\Stock\Purchases;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Balances\Abstract\BalanceAbstract;
use App\Movements\Facades\Movement;
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
                    dd(
                        ($oldBalance->avg_price + $balance['price']),
                        ($oldBalance->qty  + $balance['qty']),
                        $balance['price'],
                        $balance['qty'],
                        $price 
                    );
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
     * delete
     * @param Purchases $purchases
     * @param int $id
     * @return bool
     */
    public function delete(
        Purchases $purchases,
        int $id
    ): bool {

        try {

            DB::beginTransaction();
            /*
            *  find purchase item
            */
            $details = $purchases->details()->find($id);
            if (!$details) return false;

            $this->model = $purchases->store;

            /*
            *  store delete move
            */
            $this->model->move()->where([
                'material_id' => $details->material_id,
                'invoice_nr' => $purchases->serial_nr
            ])->delete();

            /*
            *  update store balance
            */
            $this->model->balance()->where([
                'material_id' => $details->material_id
            ])->update([
                'qty' => DB::raw('qty - ' . $details->qty),
                'avg_price' => DB::raw('avg_price - ' . $details->price)
            ]);

            /*
            *  delete purchase item
            */
            $details->delete();

            DB::commit();

            return true;
        } catch (\Throwable $e) {
            Log::error('Purchase details deleted failed: ' . $e->getMessage(), [
                'purchases' => $purchases,
                'id' => $id,
            ]);
            return false;
        }
    }
}
