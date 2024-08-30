<?php

namespace App\Movements;

use App\Enums\MaterialMove;
use App\Models\Stock\Section;
use App\Models\Stock\Exchange;
use App\Models\Stock\Purchases;
use App\Balances\Facades\Balance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Stock\PurchasesDetails;
use App\Models\Stock\SectionMaterialMove;
use App\Movements\Abstract\MovementAbstract;
use Illuminate\Database\Eloquent\Collection;
use App\Movements\Interface\MovementInterface;

class SectionMaterialMovement extends MovementAbstract implements MovementInterface
{

    protected SectionMaterialMove $model;

    /**
     * create
     * @param Section $section
     * @return bool
     */
    public function create(
        $section,
    ): bool {
        try {

            if (!$this->movement) return false;

            DB::beginTransaction();

            // Handle different types of movements
            $this->handleMovementType($section);

            // Validate and update balance
            $result = $this->updateBalance($section);

            if ($result) {
                DB::commit();
                return $result;
            }
        } catch (\Throwable $e) {
            Log::error('Movement creation failed: ' . $e->getMessage(), [
                'movement' => $this->movement,
                'section' => $section,
            ]);
            DB::rollBack();
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

            $section = $purchases->section;

            $details = $purchases->details()->find($id);

            if (!$details) return false;

            /*
            *  store delete move
            */
            $section->move()->where([
                'material_id' => $details->material_id,
                'invoice_nr' => $purchases->serial_nr
            ])->delete();

            /*
            *  update store balance
            */
            $oldBalance = $section->balance()->where('material_id', $details->material_id)->first();

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
            DB::rollBack();
            return false;
        }
    }


    /**
     * deleteExchangeMovement
     * @param Exchange $exchange
     * @param int $id
     * @return bool
     */
    public function deleteExchangeMovement(
        Exchange $exchange,
        int $id,
    ): bool {

        try {

            DB::beginTransaction();

            $section = $exchange->section;

            $details = $exchange->details()->find($id);

            if (!$details) return false;

            /*
            *  store delete move
            */
            $section->move()->where([
                'material_id' => $details->material_id,
                'order_nr' => $exchange->order_nr
            ])->delete();

            /*
            *  update store balance
            */
            $oldBalance = $section->balance()->where('material_id', $details->material_id)->first();

            $qty = $oldBalance->qty -= $details->qty;

            if ($qty < 0) return false;
            $oldBalance->update([
                'qty' => $qty,
            ]);

            /*
            *  delete exchange item
            */
            $details->delete();

            DB::commit();

            return true;
        } catch (\Throwable $e) {
            Log::error('exchange details deleted failed: ' . $e->getMessage(), [
                'exchange' => $exchange,
                'id' => $id,
            ]);
            DB::rollBack();
            return false;
        }
    }

    /**
     * handleMovementType
     * @param Section $section
     * @return void
     */
    private function handleMovementType(
        Section $section
    ): void {
        match ($this->type) {
            MaterialMove::PURCHASES->value => $this->createPurchasesMovement($section),
            MaterialMove::EXCHANGE->value => $this->createExchangeMovement($section),
            default => throw new \Exception('Unsupported movement type: ' . $this->type),
        };
    }

    /**
     * purchasesMovement
     * @param Section $section
     * @return void
     */
    private function createPurchasesMovement(
        Section $section
    ): void {
        /*
        * material move inside store
        */
        foreach ($this->movement as &$move) {

            $existingMovement = $section->move()->where([
                'material_id' => $move['material_id'],
                'invoice_nr' => $move['invoice_nr']
            ])->first();
            $section->move()->updateOrCreate(
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
                $move['qty'] -= $existingMovement->qty;
            }
        }
    }

    /**
     * createExchangeMovement
     * @param Section $section
     * @return void
     */
    private function createExchangeMovement(
        Section $section
    ): void {

        /*
            * material move inside section
        */
        foreach ($this->movement as &$move) {
            $existingMovement = $section->move()->where([
                'material_id' => $move['material_id'],
                'order_nr' => $move['order_nr']
            ])->first();
            $section->move()->updateOrCreate(
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
                $move['qty'] -= $existingMovement->qty;
            }
        }
    }

    /**
     * updateBalance
     * @param Section $store
     * @return bool
     */
    private function updateBalance(
        Section $section
    ): bool {
        return match ($this->type) {
            MaterialMove::PURCHASES->value => Balance::sectionBalance()->validate($this->movement)->purchasesBalance($section),
            MaterialMove::EXCHANGE->value => Balance::sectionBalance()->validate($this->movement)->exchangeBalance($section),
            default => false,
        };
    }
}
