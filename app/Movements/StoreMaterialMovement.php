<?php

namespace App\Movements;

use App\Enums\MaterialMove;
use App\Models\Stock\Store;
use App\Balances\Facades\Balance;
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
            DB::beginTransaction();

            if (!$this->movement) return false;


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
     * handleMovementType
     * @param Store $store
     * @return void
     */
    private function handleMovementType(
        Store $store
    ): void {
        match ($this->type) {
            MaterialMove::PURCHASES->value => $this->purchasesMovement($store),
            default => throw new \Exception('Unsupported movement type: ' . $this->type),
        };
    }

    /**
     * purchasesMovement
     * @param Store $store
     * @return void
     */
    private function purchasesMovement(
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
     * updateBalance
     * @param Store $store
     * @return bool
     */
    private function updateBalance(
        Store $store
    ): bool {
        return match ($this->type) {
            MaterialMove::PURCHASES->value => Balance::storeBalance()->validate($this->movement)->purchasesBalance($store),
            default => false,
        };
    }
}
