<?php

namespace App\Services;

use App\Enums\MaterialMove;
use Illuminate\Http\Request;
use App\Models\Stock\Exchange;
use Illuminate\Support\Facades\DB;
use App\Movements\Facades\Movement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ExchangeService
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
    public function store(
        array $request
    ) {
        try {

            $data = $this->collectExchangeData($request);

            if ($request['image'] != 'undefined') {
                $data['image'] = $this->storeExchangeImage($request['image']);
            }


            DB::beginTransaction();

            /*
            * store new exchange
            */

            $exchange = Exchange::create($data);

            $request['materialArray'] = json_decode($request['materialArray'], true);

            // // // Check if decoding was successful
            if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($request['materialArray'] as $material) {
                    // Create the exchange details record
                    $exchange->details()->create([
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

            $this->collectMaterialMovement($exchange->load('details'));

            $storeMaterialMove =  Movement::storeMaterialMovement()->validate(MaterialMove::EXCHANGE->value, $this->movement)->create($exchange->store);

            $sectionMaterialMove =  Movement::sectionMaterialMovement()->validate(MaterialMove::EXCHANGE->value, $this->movement)->create($exchange->section);

            if ($storeMaterialMove && $sectionMaterialMove) {
                DB::commit();
                return true;
            }
        } catch (\Throwable $e) {
            Log::error('exchange creation failed: ' . $e->getMessage(), [
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
        Exchange $exchange,
    ): bool {
        try {

            $data = $this->collectExchangeData($request);

            if ($request['image'] != 'undefined') {
                $data['image'] = $this->storeExchangeImage($request['image']);
            }

            DB::beginTransaction();

            /*
            * update invoice
            */
            $exchange->update($data);

            $request['materialArray'] = json_decode($request['materialArray'], true);

            // // // // Check if decoding was successful
            if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($request['materialArray'] as $material) {
                    // Create the exchange details record
                    $exchange->details()->updateOrCreate(
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

            $this->collectMaterialMovement($exchange->load('details'));

            $storeMaterialMove =  Movement::storeMaterialMovement()->validate(MaterialMove::EXCHANGE->value, $this->movement)->create($exchange->store);

            $sectionMaterialMove =  Movement::sectionMaterialMovement()->validate(MaterialMove::EXCHANGE->value, $this->movement)->create($exchange->section);

            if ($storeMaterialMove && $sectionMaterialMove) {
                DB::commit();
                return true;
            }
        } catch (\Throwable $e) {
            Log::error('exchange updating failed: ' . $e->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * delete  
     * @param  Exchange  $exchange
     * @param  int $id
     * @return bool
     */
    public function delete(
        Exchange  $exchange,
        int $id
    ): bool {

        try {

            DB::beginTransaction();

            if (!$exchange->hasDetails()) return false;

            $details = $exchange->details()->find($id);

            if (!$details) {
                throw ValidationException::withMessages([
                    'error' => 'the exchange details not found'
                ]);
            }

            $storeMaterialMove =  Movement::storeMaterialMovement()->deleteExchangeMovement($exchange, $details);

            $sectionMaterialMove = Movement::sectionMaterialMovement()->deleteExchangeMovement($exchange, $details);

            /*
            *  delete exchange item
            */
            $details->delete();

            DB::commit();

            return $storeMaterialMove && $sectionMaterialMove;
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('exchange details deleting failed: ' . $e->getMessage(), [
                'exchange' => $exchange,
            ]);
            DB::rollBack();
            throw $e;
        }
    }



    /**
     * storeExchangeImage
     * @param  $image
     * @return string
     */
    private function storeExchangeImage($image): string
    {
        $extension = $image->getClientOriginalExtension();
        $fileName = time() . '_' . rand(1000, 9999) . '.' . $extension;
        $path = 'stock/images/exchanges';
        $image->move(public_path($path), $fileName);
        return asset($path . '/' . $fileName);
    }


    /**
     * collectExchangeData
     * @param array $params
     * @return array
     */
    private function collectExchangeData(
        array $params
    ): array {
        return [
            'order_nr' => $params['order_nr'],
            'exchange_date' => $params['exchange_date'],
            'user_id' => Auth::id(),
            'notes' => $params['notes'],
            'section_id' => $params['section_id'],
            'store_id' => $params['store_id'],
            'total' => $params['total'] * 100,
        ];
    }

    /**
     * collectMaterialMovement
     * @param Exchange $exch
     * @return void
     */
    private function collectMaterialMovement(
        Exchange $exchange
    ): void {

        $exchange->details->map(function ($item) use ($exchange) {
            return [
                array_push($this->movement, [
                    'order_nr' => $exchange->id,
                    'material_id' => $item->material_id,
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'type' => MaterialMove::EXCHANGE->value
                ])
            ];
        });
    }
}
