<?php

namespace App\Invoices;

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

class Invoice implements InvoiceInterface
{
    /*
     * The attributes that hold material move.
     *
     * @var array<int, string>
    */
    protected $movement = [];

    public function __construct(
        protected SessionManager $session,
        protected Request $request
    ) {}

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

            if ($params['purchases_image'] != 'undefined') {
                $data['image'] = $this->storeInvoiceImage($params['purchases_image']);
            }

            if ($params['purchases_method'] === PurchasesMethod::STORES->value) {
                $data['store_id'] = $params['store_id'];
            } elseif ($params['purchases_method'] === PurchasesMethod::SECTIONS->value) {
                $data['section_id'] = $params['section_id'];
            }

            DB::beginTransaction();
            /*
            * store new invoice
            */
            $purchases = Purchases::create($data);

            $params['materialArray'] = json_decode($params['materialArray'], true);

            // // Check if decoding was successful
            if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($params['materialArray'] as $material) {
                    // Create the purchase details record
                    $purchases->details()->create([
                        'material_id' => $material['material_id'],
                        'expire_date' => $material['expire_date'],
                        'qty' => $material['qty'],
                        'price' => $material['price'] * 100,
                        'discount' => $material['discount'] * 100,
                        'total' => $material['total'] * 100,
                    ]);
                }
            } else {
                // Handle JSON decoding error
                Log::error('Error decoding JSON: ' . json_last_error_msg());
                throw new \Exception('Error decoding JSON: ' . json_last_error_msg(), 1);
            };

            $this->collectMaterialMovement($purchases->load('details'));
            $result =  match ($purchases->purchases_method) {
                PurchasesMethod::STORES->value => Movement::storeMaterialMovement()->validate(MaterialMove::PURCHASES->value, $this->movement)->create($purchases->store),
                PurchasesMethod::SECTIONS->value => Movement::sectionMaterialMovement()->validate(MaterialMove::PURCHASES->value, $this->movement)->create($purchases->section),
                default => throw new \Exception("Un supported purchase method", 1),
            };
            if ($result) {
                DB::commit();
                return $result;
            }
            return $result;
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Purchase creation failed: ' . $e->getMessage(), [
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
     * @param $purchase,
     * @return bool
     */
    public function update(
        array $params,
        $purchase,
    ): bool {
        try {

            $data = $this->collectInvoiceData($params);

            if ($params['purchases_image'] != 'undefined') {
                $data['image'] = $this->storeInvoiceImage($params['purchases_image']);
            }

            if ($params['purchases_method'] === PurchasesMethod::STORES->value) {
                $data['store_id'] = $params['store_id'];
            } elseif ($params['purchases_method'] === PurchasesMethod::SECTIONS->value) {
                $data['section_id'] = $params['section_id'];
            }
            DB::beginTransaction();

            /*
            * update invoice
            */
            $purchase->update($data);

            $params['materialArray'] = json_decode($params['materialArray'], true);

            // Check if decoding was successful
            if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($params['materialArray'] as $material) {
                    $material['price'] = trim($material['price']);
                    $material['qty'] = trim($material['qty']);
                    // Create the purchase details record
                    $purchase->details()->updateOrCreate(
                        [
                            'material_id' => $material['material_id'],
                        ],
                        [
                            'expire_date' => $material['expire_date'],
                            'qty' => $material['qty'],
                            'price' => $material['price'] * 100,
                            'discount' => $material['discount'] * 100,
                            'total' => $material['total'] * 100,
                        ]
                    );
                }
            } else {
                // Handle JSON decoding error
                Log::error('Error decoding JSON: ' . json_last_error_msg());
            }

            $this->collectMaterialMovement($purchase->load('details'));
            $result =  match ($purchase->purchases_method) {
                PurchasesMethod::STORES->value => Movement::storeMaterialMovement()->validate(MaterialMove::PURCHASES->value, $this->movement)->create($purchase->store),
                PurchasesMethod::SECTIONS->value => Movement::sectionMaterialMovement()->validate(MaterialMove::PURCHASES->value, $this->movement)->create($purchase->section),
                default => throw new \Exception("Un supported purchase method", 1),
            };
            if ($result) {
                DB::commit();
                return $result;
            }
            return $result;
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Purchase creation failed: ' . $e->getMessage(), [
                'data' => $data,
                'params' => $params,
            ]);
            DB::rollBack();
            return false;
        }
    }


    /**
     * delete  
     * @param  Purchases $purchases
     * @param  int $id
     * @return bool
     */
    public function delete(
        Purchases $purchases,
        int $id
    ): bool {
        if ($purchases->hasDetails()) {
            $result =  match ($purchases->purchases_method) {
                PurchasesMethod::STORES->value => Movement::storeMaterialMovement()->deletePurchaseMovement($purchases, $id),
                PurchasesMethod::SECTIONS->value => Movement::sectionMaterialMovement()->deletePurchaseMovement($purchases, $id),
                default => throw new \Exception("Un supported purchase method", 1),
            };
            return $result;
        } else {
            return false;
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
        $path = 'stock/images/purchases';
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
            'purchases_method' => $params['purchases_method'],
            'supplier_id' => $params['supplier_id'],
            'user_id' => 10,
            'purchases_date' => $params['purchases_date'],
            'payment_type' => $params['payment_type'],
            'tax' => $params['tax'],
            'total' => $params['sumTotal'] * 100,
            'note' => $params['notes'],
            'section_id' => null,
            'store_id' => null,
        ];
    }

    /**
     * collectMaterialMovement
     * @param Purchases $purchases
     * @return void
     */
    private function collectMaterialMovement(
        Purchases $purchases
    ): void {
        $purchases->details->map(function ($item) use ($purchases) {
            return [
                array_push($this->movement, [
                    'invoice_nr' => $purchases->serial_nr,
                    'material_id' => $item->material_id,
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'type' => MaterialMove::PURCHASES->value
                ])
            ];
        });
    }
}
