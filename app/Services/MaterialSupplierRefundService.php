<?php

namespace App\Services;

use App\Enums\MaterialMove;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\PurchasesMethod;
use App\Models\Stock\Purchases;
use App\Balances\Facades\Balance;
use Illuminate\Support\Facades\DB;
use App\Movements\Facades\Movement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Session;
use App\Invoices\Invoice\InvoiceInterface;
use App\Models\Stock\MaterialSupplierRefund;
use Illuminate\Validation\ValidationException;

class MaterialSupplierRefundService
{

    /*
     * The attributes that hold material move.
     *
     * @var array<int, string>
    */
    protected $movement = [];

    /**
     * store
     * @param array $params
     * @return bool
     */
    public function create(
        array $params
    ): bool {
        try {

            $data = $this->collectInvoiceData($params);

            if ($params['refund_image'] != 'undefined') {
                $data['refund_image'] = $this->storeInvoiceImage($params['refund_image']);
            }

            if ($params['refund_method'] === PurchasesMethod::STORES->value) {
                $data['store_id'] = $params['store_id'];
            } elseif ($params['refund_method'] === PurchasesMethod::SECTIONS->value) {
                $data['section_id'] = $params['section_id'];
            }

            DB::beginTransaction();
            /*
            * store new invoice
            */
            $supplier_refund = MaterialSupplierRefund::create($data);

            $params['materialArray'] = json_decode($params['materialArray'], true);

            // // Check if decoding was successful
            if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($params['materialArray'] as $material) {
                    // Create the refund details record
                    $supplier_refund->details()->create([
                        'material_id' => $material['material_id'],
                        'expire_date' => $material['expire_date'],
                        'qty' => $material['qty'],
                        'price' => $material['price'] * 100,
                        'total' => $material['total'] * 100,
                    ]);
                }
            } else {
                // Handle JSON decoding error
                Log::error('Error decoding JSON: ' . json_last_error_msg());
                throw new \Exception('Error decoding JSON: ' . json_last_error_msg(), 1);
            };

            $this->collectMaterialMovement($supplier_refund->load('details'));

            $result =  match ($supplier_refund->refund_method) {
                PurchasesMethod::STORES->value => Movement::storeMaterialMovement()->validate(MaterialMove::SUPPLIER_REFUND->value, $this->movement)->create($supplier_refund->store),
                PurchasesMethod::SECTIONS->value => Movement::sectionMaterialMovement()->validate(MaterialMove::SUPPLIER_REFUND->value, $this->movement)->create($supplier_refund->section),
                default => throw new \Exception("Un supported purchase method", 1),
            };

            if ($result) {
                DB::commit();
                return $result;
            }
            return $result;
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Supplier refund creation failed: ' . $e->getMessage(), [
                'data' => $data,
                'params' => $params,
            ]);
            DB::rollBack();
            return false;
        }
    }

    /**
     * update
     * @param array $params
     * @param $supplier_refund,
     * @return bool
     */
    public function update(
        array $params,
        $supplier_refund,
    ): bool {
        try {

            $data = $this->collectInvoiceData($params);

            if ($params['refund_image'] != 'undefined') {
                $data['refund_image'] = $this->storeInvoiceImage($params['refund_image']);
            }

            if ($params['refund_method'] === PurchasesMethod::STORES->value) {
                $data['store_id'] = $params['store_id'];
            } elseif ($params['refund_method'] === PurchasesMethod::SECTIONS->value) {
                $data['section_id'] = $params['section_id'];
            }

            DB::beginTransaction();

            /*
            * update invoice
            */
            $supplier_refund->update($data);

            $params['materialArray'] = json_decode($params['materialArray'], true);

            // Check if decoding was successful
            if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($params['materialArray'] as $material) {
                    // Create the purchase details record
                    $supplier_refund->details()->updateOrCreate(
                        [
                            'material_id' => $material['material_id'],
                        ],
                        [
                            'expire_date' => $material['expire_date'],
                            'qty' => $material['qty'],
                            'price' => $material['price'] * 100,
                            'total' => $material['total'] * 100,
                        ]
                    );
                }
            } else {
                // Handle JSON decoding error
                Log::error('Error decoding JSON: ' . json_last_error_msg());
            }

            $this->collectMaterialMovement($supplier_refund->load('details'));

            $result =  match ($supplier_refund->refund_method) {
                PurchasesMethod::STORES->value => Movement::storeMaterialMovement()->validate(MaterialMove::SUPPLIER_REFUND->value, $this->movement)->create($supplier_refund->store),
                PurchasesMethod::SECTIONS->value => Movement::sectionMaterialMovement()->validate(MaterialMove::SUPPLIER_REFUND->value, $this->movement)->create($supplier_refund->section),
                default => throw new \Exception("Un supported purchase method", 1),
            };

            if ($result) {
                DB::commit();
                return $result;
            }
            return $result;
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Supplier refund creation failed: ' . $e->getMessage(), [
                'data' => $data,
                'params' => $params,
            ]);
            DB::rollBack();
            throw $e;
        }
    }


    /**
     * delete  
     * @param  MaterialSupplierRefund $supplier_refund,
     * @param  int $id
     * @return bool
     */
    public function delete(
        MaterialSupplierRefund $supplier_refund,
        int $id
    ): bool {

        try {

            DB::beginTransaction();

            if (!$supplier_refund->hasDetails()) return false;

            $details = $supplier_refund->details()->find($id);

            if (!$details) {
                throw ValidationException::withMessages([
                    'error' => 'the material halk details not found'
                ]);
            }

            /*
            * delete material refund movement
            */

            $result =  match ($supplier_refund->refund_method) {
                PurchasesMethod::STORES->value => Movement::storeMaterialMovement()->deleteSupplierRefundMovement($supplier_refund, $details),
                PurchasesMethod::SECTIONS->value => Movement::sectionMaterialMovement()->deleteSupplierRefundMovement($supplier_refund, $details),
                default => throw new \Exception("Un supported purchase method", 1),
            };

            if ($result) {
                /*
                *  delete halk item
                */
                $details->delete();

                DB::commit();
            }
            return $result;
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('supplier refund details deleting failed: ' . $e->getMessage(), [
                'supplier_refund' => $supplier_refund,
            ]);
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * storeInvoiceImage
     * @param  $image
     * @return string
     */
    private function storeInvoiceImage($image): string
    {
        $extension = $image->getClientOriginalExtension();
        $fileName = time() . '_' . rand(1000, 9999) . '.' . $extension;
        $path = 'stock/images/supplier/refund';
        $image->move(public_path($path), $fileName);
        return asset($path . '/' . $fileName);
    }

    /**
     * collectInvoiceData
     * @param array $params
     * @return array
     */
    private function collectInvoiceData(
        array $params
    ): array {
        return [
            'serial_nr' => $params['serial_nr'],
            'refund_method' => $params['refund_method'],
            'supplier_id' => $params['supplier_id'],
            'user_id' =>  Auth::id(),
            'refund_date' => $params['refund_date'],
            'total' => $params['total'] * 100,
            'notes' => $params['notes'],
            'section_id' => null,
            'store_id' => null,
        ];
    }

    /**
     * collectMaterialMovement
     * @param MaterialSupplierRefund $supplier_refund
     * @return void
     */
    private function collectMaterialMovement(
        MaterialSupplierRefund $supplier_refund
    ): void {

        $supplier_refund->details->map(function ($item) use ($supplier_refund) {
            return [
                array_push($this->movement, [
                    'supplier_refund_nr' => $supplier_refund->id,
                    'material_id' => $item->material_id,
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'type' => MaterialMove::SUPPLIER_REFUND->value
                ])
            ];
        });
    }
}
