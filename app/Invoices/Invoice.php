<?php

namespace App\Invoices;

use Illuminate\Http\Request;
use App\Enums\PurchasesMethod;
use App\Models\Stock\Purchases;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Session;
use App\Invoices\Invoice\InvoiceInterface;

class Invoice implements InvoiceInterface
{


    public function __construct(
        protected SessionManager $session,
        protected Request $request
    ) {}

    /**
     * @param array $params
     * store
     */
    public function create(
        array $params
    ) {

        $data = [
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

        if ($params['purchases_image'] != 'undefined') {
            $data['image'] = $this->storeInvoiceImage($data['purchases_image']);
        }

        if ($params['purchases_method'] === PurchasesMethod::STORES->value) {
            $data['store_id'] = $data['store_id'];
        } elseif ($params['purchases_method'] === PurchasesMethod::SECTIONS->value) {
            $data['section_id'] = $data['section_id'];
        }

        try {
            $result = DB::transaction(function () use ($data, $params) {
                Log::info('Starting transaction...');
                $purchases = Purchases::create($data);
                if (!$purchases) {
                    Log::error('Failed to create purchase record.');
                    throw new \Exception('Failed to create purchase record');
                    return false;
                }
                foreach ($params['materialArray'] as $material) {
                    try {
                        // Create a new purchase detail record for each material
                        $purchases->details()->create([
                            'material_id' => $material['material_id'],
                            'expire_date' => $material['expire_date'],
                            'qty' => $material['qty'],
                            'price' => $material['price'] * 100,
                            'discount' => $material['discount'] * 100,
                            'total' => $material['total'] * 100,
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to create purchase detail: ' . $e->getMessage(), [
                            'material' => $material,
                        ]);
                        throw new \Exception('Failed to create purchase details records');

                        return false;
                    }
                };

                Log::info('Transaction successful.');
                return true;
            }, 3);
            return $result;
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Purchase creation failed: ' . $e->getMessage(), [
                'data' => $data,
                'params' => $params,
            ]);
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
}
