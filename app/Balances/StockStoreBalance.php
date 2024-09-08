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
     * purchasesBalance
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
                    $qty = $oldBalance->qty + $balance['qty'];

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
            DB::rollBack();
            return false;
        }
    }

    /**
     * exchangePurchase
     * @param Store $store
     * @return bool
     */
    public function exchangeBalance(
        $store
    ): bool {

        try {

            if (!$this->balance) return false;

            /*
            * increase balance of section
            */

            foreach ($this->balance as $balance) {
                $oldBalance = $store->balance()->where('material_id', $balance['material_id'])->first();

                $qty = $oldBalance->qty - $balance['qty'];

                if ($qty < 0) return false;

                $oldBalance->update([
                    'qty' => $qty,
                ]);
            }
            return true;
        } catch (\Throwable $e) {
            Log::error('increase store balance creation failed: ' . $e->getMessage(), [
                'balance' => $this->balance,
                'params' => $store,
            ]);
            DB::rollBack();
            return false;
        }
    }

    /**
     * halkBalance
     * @param Store $store
     * @return bool
     */
    public function halkBalance(
        $store
    ): bool {

        try {

            if (!$this->balance) return false;

            /*
            * increase balance of section
            */

            foreach ($this->balance as $balance) {
                $oldBalance = $store->balance()->where('material_id', $balance['material_id'])->first();

                $qty = $oldBalance->qty - $balance['qty'];

                if ($qty < 0) return false;

                $oldBalance->update([
                    'qty' => $qty,
                ]);
            }
            return true;
        } catch (\Throwable $e) {
            Log::error('halk store balance creation failed: ' . $e->getMessage(), [
                'balance' => $this->balance,
                'params' => $store,
            ]);
            DB::rollBack();
            return false;
        }
    }

    /**
     * halkItemBalance
     * @param Store $store
     * @return bool
     */
    public function halkItemBalance(
        $store
    ): bool {

        try {

            if (!$this->balance) return false;

            /*
            * increase balance of section
            */

            foreach ($this->balance as $balance) {
                $oldBalance = $store->balance()->where('material_id', $balance['material_id'])->first();

                $qty = $oldBalance->qty - $balance['qty'];

                if ($qty < 0) return false;

                $oldBalance->update([
                    'qty' => $qty,
                ]);
            }
            return true;
        } catch (\Throwable $e) {
            Log::error('halk item store balance creation failed: ' . $e->getMessage(), [
                'balance' => $this->balance,
                'params' => $store,
            ]);
            DB::rollBack();
            return false;
        }
    }

    /**
     * increaseBalance
     * @param Store $store
     * @return bool
     */

    public function increaseBalance(
        $store
    ): bool {
        try {

            if (!$this->balance) return false;
            /*
            * increase balance of store
            */
            foreach ($this->balance as $balance) {
                $oldBalance = $store->balance()->where('material_id', $balance['material_id'])->first();

                if ($oldBalance) {
                    $qty = $oldBalance->qty + $balance['qty']; // Using + instead of +=

                    $oldBalance->update(['qty' => $qty]);
                } else {
                    $store->balance()->create([
                        'store_id' => $store->id,
                        'material_id' => $balance['material_id'],
                        'qty' => $balance['qty'],
                        'avg_price' => $balance['price'],
                    ]);
                }
            }


            return true;
        } catch (\Throwable $e) {
            Log::error('increase store balance creation failed: ' . $e->getMessage(), [
                'balance' => $this->balance,
                'params' => $store,
            ]);
            DB::rollBack();
            return false;
        }
    }


    /**
     * decreaseBalance
     * @param Store $store
     * @return bool
     */

    public function decreaseBalance(
        $store
    ): bool {

        try {

            if (!$this->balance) return false;
            /*
            * decrease balance of store
            */
            foreach ($this->balance as $balance) {
                $oldBalance = $store->balance()->where('material_id', $balance['material_id'])->first();
                $qty = $oldBalance->qty - $balance['qty'];
                if ($qty < 0) return false;
                $oldBalance->update([
                    'qty' => $qty,
                ]);
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('decrease store balance creation failed: ' . $e->getMessage(), [
                'balance' => $this->balance,
                'params' => $store,
            ]);
            DB::rollBack();
            return false;
        }
    }

    /**
     * currentBalanceByMaterial
     * @param Material $material
     * @param int $id
     * @return int
     */
    public function currentBalanceByMaterial(
        Material $material,
        int $id
    ): int {
        $this->model = Store::findOrFail($id);
        $balance =  $this->model->balance()->whereBelongsTo($material)->first()->qty ?? 0;
        return $balance;
    }
}
