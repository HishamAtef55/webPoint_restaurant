<?php

namespace App\Services;

use App\Enums\MaterialMove;
use Illuminate\Http\Request;
use App\Models\Stock\Exchange;
use App\Models\Stock\MaterialStoreRefund;
use Illuminate\Support\Facades\DB;
use App\Movements\Facades\Movement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MaterialStoreRefundService
{
    /*
     * The attributes that hold material move.
     *
     * @var array<int, string>
    */
    protected $movement = [];

    /**
     * store
     * @param array $request
     * @return void
     */
    public function create(
        array $request
    ) {
        try {

            $data = $this->collectStoreRefundData($request);

            if ($request['refund_image'] != 'undefined') {
                $data['refund_image'] = $this->storeRefundImage($request['refund_image']);
            }


            DB::beginTransaction();

            /*
            * store new store refund
            */

            $store_refund = MaterialStoreRefund::create($data);

            $request['materialArray'] = json_decode($request['materialArray'], true);

            // // // Check if decoding was successful
            if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($request['materialArray'] as $material) {
                    // Create the refund details record
                    $store_refund->details()->create([
                        'material_id' => $material['material_id'],
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

            $this->collectMaterialMovement($store_refund->load('details'));

            $storeMaterialMove =  Movement::storeMaterialMovement()->validate(MaterialMove::STORE_REFUND->value, $this->movement)->create($store_refund->store);

            $sectionMaterialMove =  Movement::sectionMaterialMovement()->validate(MaterialMove::STORE_REFUND->value, $this->movement)->create($store_refund->section);

            if ($storeMaterialMove && $sectionMaterialMove) {
                DB::commit();
            }
            return $storeMaterialMove && $sectionMaterialMove;
        } catch (\Throwable $e) {
            Log::error('store refund creation failed: ' . $e->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * update
     * @param array $params
     * @param Exchange $exchange,
     * @return bool
     */
    public function update(
        array $request,
        MaterialStoreRefund $store_refund,
    ): bool {
        try {

            $data = $this->collectStoreRefundData($request);

            if ($request['refund_image'] != 'undefined') {
                $data['refund_image'] = $this->storeRefundImage($request['refund_image']);
            }


            DB::beginTransaction();

            /*
            * update store refund
            */
            $store_refund->update($data);

            $request['materialArray'] = json_decode($request['materialArray'], true);

            // // // // Check if decoding was successful
            if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($request['materialArray'] as $material) {
                    // Create the exchange details record
                    $store_refund->details()->updateOrCreate(
                        [
                            'material_id' => $material['material_id'],
                        ],
                        [
                            'qty' => $material['qty'],
                            'price' => $material['price'] * 100,
                            'total' => $material['total'] * 100,
                        ]
                    );
                }
            } else {
                // Handle JSON decoding error
                Log::error('Error decoding JSON: ' . json_last_error_msg());
                throw new \Exception('Error decoding JSON: ' . json_last_error_msg(), 1);
            };

            $this->collectMaterialMovement($store_refund->load('details'));

            $storeMaterialMove =  Movement::storeMaterialMovement()->validate(MaterialMove::STORE_REFUND->value, $this->movement)->create($store_refund->store);

            $sectionMaterialMove =  Movement::sectionMaterialMovement()->validate(MaterialMove::STORE_REFUND->value, $this->movement)->create($store_refund->section);

            if ($storeMaterialMove && $sectionMaterialMove) {
                DB::commit();
            }
            return $storeMaterialMove && $sectionMaterialMove;
        } catch (\Throwable $e) {
            Log::error('store refund updating failed: ' . $e->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }



    /**
     * delete  
     * @param  MaterialStoreRefund  $refund,
     * @param  int $id
     * @return bool
     */
    public function delete(
        MaterialStoreRefund  $refund,
        int $id
    ): bool {

        try {

            DB::beginTransaction();

            if (!$refund->hasDetails()) return false;

            $details = $refund->details()->find($id);

            if (!$details) {
                throw ValidationException::withMessages([
                    'error' => 'the store refund details not found'
                ]);
            }

            $storeMaterialMove =  Movement::storeMaterialMovement()->deleteRefundMovement($refund, $details);

            $sectionMaterialMove = Movement::sectionMaterialMovement()->deleteRefundMovement($refund, $details);

            if($storeMaterialMove && $sectionMaterialMove){

                /*
                *  delete exchange item
                */
    
                $details->delete();
    
                DB::commit();
            }

            return $storeMaterialMove && $sectionMaterialMove;
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('store refund details deleting failed: ' . $e->getMessage(), [
                'refund' => $refund,
            ]);
            DB::rollBack();
            throw $e;
        }
    }



    /**
     * storeRefundImage
     * @param  $image
     * @return string
     */
    private function storeRefundImage($image): string
    {
        $extension = $image->getClientOriginalExtension();
        $fileName = time() . '_' . rand(1000, 9999) . '.' . $extension;
        $path = 'stock/images/store/refund';
        $image->move(public_path($path), $fileName);
        return asset($path . '/' . $fileName);
    }


    /**
     * collectStoreRefundData
     * @param array $params
     * @return array
     */
    private function collectStoreRefundData(
        array $params
    ): array {
        return [
            'serial_nr' => $params['serial_nr'],
            'refund_date' => $params['refund_date'],
            'user_id' => 10, #Auth::id(),
            'notes' => $params['notes'],
            'section_id' => $params['section_id'],
            'store_id' => $params['store_id'],
            'total' => $params['total'] * 100,
        ];
    }

    /**
     * collectMaterialMovement
     * @param MaterialStoreRefund $store_refund
     * @return void
     */
    private function collectMaterialMovement(
        MaterialStoreRefund $store_refund
    ): void {

        $store_refund->details->map(function ($item) use ($store_refund) {
            return [
                array_push($this->movement, [
                    'refund_nr' => $store_refund->id,
                    'material_id' => $item->material_id,
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'type' => MaterialMove::STORE_REFUND->value
                ])
            ];
        });
    }
}
