<?php

namespace App\Movements;

use App\Enums\MaterialMove;
use App\Models\Stock\Section;
use App\Models\Stock\Exchange;
use App\Models\Stock\Purchases;
use App\Balances\Facades\Balance;
use App\Models\Stock\MaterialHalk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Stock\MaterialHalkItem;
use App\Models\Stock\MaterialTransfer;
use App\Models\Stock\SectionMaterialMove;
use App\Models\Stock\MaterialSupplierRefund;
use App\Movements\Abstract\MovementAbstract;
use App\Models\Stock\MaterialHalkItemDetails;
use App\Models\Stock\MaterialMovementDetails;
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
            }
            return $result;
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
                'invoice_nr' => $purchases->id
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
     * 
     * @param Exchange $exchange
     * @param MaterialMovementDetails $details
     * @return bool
     */
    public function deleteExchangeMovement(
        Exchange $exchange,
        MaterialMovementDetails $details,
    ): bool {

        try {

            DB::beginTransaction();

            $section = $exchange->section;

            /*
            *  store delete move
            */
            $section->move()->where([
                'material_id' => $details->material_id,
                'order_nr' => $exchange->id
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


            DB::commit();

            return true;
        } catch (\Throwable $e) {
            Log::error('exchange details deleted failed: ' . $e->getMessage(), [
                'exchange' => $exchange,
                'exchange_details' => $details,
            ]);
            DB::rollBack();
            return false;
        }
    }

    /**
     * deleteHalkMovement
     * 
     * @param MaterialHalk $material_halk
     * @param MaterialMovementDetails $details
     * @return bool
     */
    public function deleteHalkMovement(
        MaterialHalk $material_halk,
        MaterialMovementDetails $details,
    ): bool {

        try {

            DB::beginTransaction();

            $section = $material_halk->section;

            /*
            *  store delete move
            */
            $section->move()->where([
                'material_id' => $details->material_id,
                'halk_nr' => $material_halk->id
            ])->delete();

            /*
            *  update store balance
            */
            $oldBalance = $section->balance()->where('material_id', $details->material_id)->first();

            $qty = $oldBalance->qty + $details->qty;

            $oldBalance->update([
                'qty' => $qty,
            ]);

            DB::commit();

            return true;
        } catch (\Throwable $e) {
            Log::error('halk details deleted failed: ' . $e->getMessage(), [
                'material_halk' => $material_halk,
                'halk_details' => $details,
            ]);
            DB::rollBack();
            return false;
        }
    }


    /**
     * deleteHalkItemMovement
     * 
     * @param MaterialHalkItem $halk_item,alk
     * @param MaterialHalkItemDetails $details
     * @return bool
     */
    public function deleteHalkItemMovement(
        MaterialHalkItem $halk_item,
        MaterialHalkItemDetails $details,
    ): bool {

        try {

            DB::beginTransaction();

            $section = $halk_item->section;

            /*
            *  store delete move
            */
            $materialIds  = $details->components->pluck('material_id')->all();

            $section->move()->whereIn('material_id', $materialIds)
                ->where('halk_item_nr', $halk_item->id)
                ->delete();
            /*
            *  update store balance
            */
            foreach ($materialIds as $materialId) {
                $oldBalance = $section->balance()->where('material_id', $materialId)->first();
                if (!$oldBalance) return false;

                $qty = $oldBalance->qty + $details->qty;

                $oldBalance->update([
                    'qty' => $qty,
                ]);
            }

            DB::commit();

            return true;
        } catch (\Throwable $e) {
            Log::error('halk item details deleted failed: ' . $e->getMessage(), [
                'halk_item' => $halk_item,
                'halk_item_details' => $details,
            ]);
            DB::rollBack();
            return false;
        }
    }


    /**
     * deleteSupplierRefundMovement
     * @param MaterialSupplierRefund $supplier_refund
     * @param MaterialMovementDetails $details,
     * @return bool
     */
    public function deleteSupplierRefundMovement(
        MaterialSupplierRefund $supplier_refund,
        MaterialMovementDetails $details,
    ): bool {

        try {

            DB::beginTransaction();

            $section = $supplier_refund->section;

            /*
            *  store delete move
            */
            $section->move()->where([
                'material_id' => $details->material_id,
                'supplier_refund_nr' => $supplier_refund->id
            ])->delete();

            /*
            *  update store balance
            */
            /*
            *  update store balance
            */

            $oldBalance = $section->balance()->where('material_id', $details['material_id'])->first();

            if (!$oldBalance) return false;

            $qty = $oldBalance->qty + $details['qty'];

            if ($qty < 0) return false;

            $oldBalance->update([
                'qty' => $qty,
            ]);


            DB::commit();

            return true;
        } catch (\Throwable $e) {
            Log::error('supplier refund details deleted failed: ' . $e->getMessage(), [
                'supplier_refund' => $supplier_refund,
                'supplier_refund_details' => $details,
            ]);
            DB::rollBack();
            return false;
        }
    }




    /**
     * createTransferFromMovement
     * @param Section $section
     * @return bool
     */
    public function createTransferFromMovement(
        $section
    ): bool {
        try {

            DB::beginTransaction();

            /*
            * material move inside section
            */
            foreach ($this->movement as &$move) {
                $existingMovement = $section->move()->where([
                    'material_id' => $move['material_id'],
                    'transfer_nr' => $move['transfer_nr']
                ])->first();
                $section->move()->updateOrCreate(
                    [
                        'material_id' => $move['material_id'],
                        'transfer_nr' => $move['transfer_nr']
                    ],
                    [
                        'qty' => $move['qty'],
                        'price' => $move['price'],
                        'type' => $move['type'],
                    ]
                );
                if ($existingMovement) {
                    $move['qty'] = $move['qty'] - $existingMovement->qty;
                }
            }

            $result = Balance::sectionBalance()->validate($this->movement)->decreaseBalance($section);
            if ($result) {

                DB::commit();

                return $result;
            }
        } catch (\Throwable $e) {
            Log::error('Transfer from movement creation failed: ' . $e->getMessage(), [
                'movement' => $this->movement,
                'store' => $section,
            ]);
            DB::rollBack();
            return false;
        }
    }


    /**
     * createTransferToMovement
     * @param Section $section
     * @return bool
     */
    public function createTransferToMovement(
        $section
    ): bool {

        try {

            DB::beginTransaction();
            /*
            * material move inside section
            */
            foreach ($this->movement as &$move) {
                $existingMovement = $section->move()->where([
                    'material_id' => $move['material_id'],
                    'transfer_nr' => $move['transfer_nr']
                ])->first();

                $section->move()->updateOrCreate(
                    [
                        'material_id' => $move['material_id'],
                        'transfer_nr' => $move['transfer_nr']
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

            $result =  Balance::sectionBalance()->validate($this->movement)->increaseBalance($section);

            if ($result) {

                DB::commit();

                return $result;
            }
        } catch (\Throwable $e) {
            Log::error('Transfer to movement creation failed: ' . $e->getMessage(), [
                'movement' => $this->movement,
                'section' => $section,
            ]);
            DB::rollBack();
            return false;
        }
    }

    /**
     * deleteTransferFromMovement
     * 
     * @param MaterialTransfer $transfer
     * @param MaterialMovementDetails $details
     * @return bool
     */
    public function deleteTransferFromMovement(
        MaterialTransfer $transfer,
        MaterialMovementDetails $details,
    ): bool {

        try {

            $section = $transfer->from_section;

            DB::beginTransaction();

            /*
            *  store delete move
            */
            $section->move()->where([
                'material_id' => $details->material_id,
                'transfer_nr' => $transfer->id
            ])->delete();

            /*
            *  update store balance
            */
            $oldBalance = $section->balance()->where('material_id', $details->material_id)->first();

            $qty = $oldBalance->qty + $details->qty;

            $oldBalance->update([
                'qty' => $qty,
            ]);

            DB::commit();

            return true;
        } catch (\Throwable $e) {
            Log::error('transfer details deleted failed: ' . $e->getMessage(), [
                'transfer' => $transfer,
                'transfer_details' => $details,
            ]);
            DB::rollBack();
            return false;
        }
    }

    /**
     * deleteTransferToMovement
     * 
     * @param  MaterialTransfer $transfer
     * @param MaterialMovementDetails $details
     * @return bool
     */
    public function deleteTransferToMovement(
        MaterialTransfer $transfer,
        MaterialMovementDetails $details,
    ): bool {

        try {

            DB::beginTransaction();

            $section = $transfer->to_section;

            /*
           *  store delete move
           */
            $section->move()->where([
                'material_id' => $details->material_id,
                'transfer_nr' => $transfer->id
            ])->delete();

            /*
           *  update store balance
           */
            $oldBalance = $section->balance()->where('material_id', $details->material_id)->first();

            $qty = $oldBalance->qty - $details->qty;

            $oldBalance->update([
                'qty' => $qty,
            ]);

            DB::commit();

            return true;
        } catch (\Throwable $e) {
            Log::error('transfer details deleted failed: ' . $e->getMessage(), [
                'transfer' => $transfer,
                'transfer_details' => $details,
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
            MaterialMove::HALK->value => $this->createHalkMovement($section),
            MaterialMove::HALKITEM->value => $this->createHalkItemMovement($section),
            MaterialMove::SUPPLIER_REFUND->value => $this->createSupplierRefundMovement($section),
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
     * createSupplierRefundMovement
     * @param Section $section
     * @return void
     */
    private function createSupplierRefundMovement(
        Section $section
    ): void {
        /*
        * material move inside store
        */
        foreach ($this->movement as &$move) {

            $existingMovement = $section->move()->where([
                'material_id' => $move['material_id'],
                'supplier_refund_nr' => $move['supplier_refund_nr']
            ])->first();
            $section->move()->updateOrCreate(
                [
                    'material_id' => $move['material_id'],
                    'supplier_refund_nr' => $move['supplier_refund_nr']
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
     * createHalkMovement
     * @param Section $section
     * @return void
     */
    private function createHalkMovement(
        Section $section
    ): void {

        /*
        * material move inside store
        */
        foreach ($this->movement as &$move) {
            $existingMovement = $section->move()->where([
                'material_id' => $move['material_id'],
                'halk_nr' => $move['halk_nr']
            ])->first();
            $section->move()->updateOrCreate(
                [
                    'material_id' => $move['material_id'],
                    'halk_nr' => $move['halk_nr']
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
     * createHalkItemMovement
     * @param Section $section
     * @return void
     */
    private function createHalkItemMovement(
        Section $section
    ): void {

        /*
        * material move inside store
        */
        foreach ($this->movement as &$move) {
            $existingMovement = $section->move()->where([
                'material_id' => $move['material_id'],
                'halk_item_nr' => $move['halk_item_nr']
            ])->first();
            $section->move()->updateOrCreate(
                [
                    'material_id' => $move['material_id'],
                    'halk_item_nr' => $move['halk_item_nr']
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
            MaterialMove::EXCHANGE->value => Balance::sectionBalance()->validate($this->movement)->increaseBalance($section),
            MaterialMove::HALK->value => Balance::sectionBalance()->validate($this->movement)->decreaseBalance($section),
            MaterialMove::HALKITEM->value => Balance::sectionBalance()->validate($this->movement)->decreaseBalance($section),
            MaterialMove::SUPPLIER_REFUND->value => Balance::sectionBalance()->validate($this->movement)->decreaseBalance($section),
            default => false,
        };
    }
}
