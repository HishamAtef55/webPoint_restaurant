<?php

namespace App\Movements;

use App\Enums\MaterialMove;
use App\Models\Stock\Store;
use App\Models\Stock\Purchases;
use App\Balances\Facades\Balance;
use App\Models\Stock\PurchasesDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Stock\StoreMaterialMove;
use App\Movements\Abstract\MovementAbstract;
use Illuminate\Database\Eloquent\Collection;
use App\Movements\Interface\MovementInterface;

class StoreMaterialMovement extends MovementAbstract implements MovementInterface
{

    protected StoreMaterialMove $model;

    /**
     * create
     * @param Store $store
     * @return bool
     */
    public function create(
        $store,
    ): bool {
        try {

            if (!$this->movement) return false;

            DB::beginTransaction();

            // Handle different types of movements
            $this->handleMovementType($store);

            // Validate and update balance
            $result = $this->updateBalance($store);

            if ($result) {
                DB::commit();
                return $result;
            }
        } catch (\Throwable $e) {
            Log::error('Movement creation failed: ' . $e->getMessage(), [
                'movement' => $this->movement,
                'store' => $store,
            ]);
            return false;
        }
    }


    /**
     * delete
     * @param Purchases $purchases
     * @return bool
     */
    public function deletePurchaseMovement(
        Purchases $purchases,
        int $id,
    ): bool {

        try {

            DB::beginTransaction();

            $store = $purchases->store;

            $details = $purchases->details()->find($id);

            if (!$details) return false;

            /*
            *  store delete move
            */
            $store->move()->where([
                'material_id' => $details->material_id,
                'invoice_nr' => $purchases->serial_nr
            ])->delete();

            /*
            *  update store balance
            */
            $oldBalance = $store->balance()->where('material_id', $details->material_id)->first();

            $qty = $oldBalance->qty -= $details->qty;

            if ($qty == 0) {
                $oldBalance->delete();
            } else {
                $price = ($oldBalance->avg_price - $details->price) /  ($qty);
                $oldBalance->update([
                    'qty' => $qty,
                    'avg_price' => $price,
                ]);
            }
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

    /**
     * handleMovementType
     * @param Store $store
     * @return void
     */
    private function handleMovementType(
        Store $store
    ): void {
        match ($this->type) {
            MaterialMove::PURCHASES->value => $this->createPurchasesMovement($store),
            MaterialMove::EXCHANGE->value => $this->createExchangeMovement($store),
            default => throw new \Exception('Unsupported movement type: ' . $this->type),
        };
    }

    /**
     * purchasesMovement
     * @param Store $store
     * @return void
     */
    private function createPurchasesMovement(
        Store $store
    ): void {
        /*
            * material move inside store
        */
        foreach ($this->movement as &$move) {

            $existingMovement = $store->move()->where([
                'material_id' => $move['material_id'],
                'invoice_nr' => $move['invoice_nr']
            ])->first();
            $store->move()->updateOrCreate(
                [
                    'material_id' => $move['material_id'],
                    'invoice_nr' => $move['invoice_nr']
                ],
                [
                    'qty' => $move['qty'],
                    'price' => $move['price'],
                    'type' => $move['type'],
                ]
            );
            if ($existingMovement) {
                $move['qty'] = $move['qty'] - $existingMovement->qty;
                $move['price'] = $move['price'] - $existingMovement->price;
            }
        }
    }


    /**
     * purchasesMovement
     * @param Store $store
     * @return void
     */
    private function createExchangeMovement(
        Store $store
    ): void {
        /*
            * material move inside store
        */
        foreach ($this->movement as &$move) {

            $existingMovement = $store->move()->where([
                'material_id' => $move['material_id'],
                'order_nr' => $move['order_nr']
            ])->first();
            $store->move()->updateOrCreate(
                [
                    'material_id' => $move['material_id'],
                    'order_nr' => $move['order_nr']
                ],
                [
                    'qty' => $move['qty'],
                    'price' => $move['price'],
                    'type' => $move['type'],
                ]
            );
            if ($existingMovement) {
                 $move['qty'] -=$existingMovement->qty;
            }
        }
    }


    /**
     * updateBalance
     * @param Store $store
     * @return bool
     */
    private function updateBalance(
        Store $store
    ): bool {
        return match ($this->type) {
            MaterialMove::PURCHASES->value => Balance::storeBalance()->validate($this->movement)->purchasesBalance($store),
            MaterialMove::EXCHANGE->value => Balance::storeBalance()->validate($this->movement)->exchangeBalance($store),
            default => false,
        };
    }
}
