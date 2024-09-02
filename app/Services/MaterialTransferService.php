<?php

namespace App\Services;

use App\Enums\MaterialMove;
use Illuminate\Http\Request;
use App\Enums\PurchasesMethod;
use App\Models\Stock\Exchange;
use App\Models\Stock\MaterialTransfer;
use Illuminate\Support\Facades\DB;
use App\Movements\Facades\Movement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MaterialTransferService
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

            $data = $this->collectMaterialTransferData($request);

            if ($request['image'] != 'undefined') {
                $data['image'] = $this->storeMaterialTransferImage($request['image']);
            }

            if ($request['transfer_type'] == PurchasesMethod::STORES->value) {
                $data = array_merge($data, [
                    'from_store_id' => $request['from_store_id'],
                    'to_store_id' => $request['to_store_id'],

                ]);
            } elseif ($request['transfer_type'] == PurchasesMethod::SECTIONS->value) {
                $data = array_merge($data, [
                    'from_section_id' => $request['from_section_id'],
                    'to_section_id' => $request['to_section_id'],
                ]);
            }

            DB::beginTransaction();

            /*
            * store new material transfer
            */

            $transfer = MaterialTransfer::create($data);

            // $request['materialArray'] = json_decode($request['materialArray'], true);

            // // // Check if decoding was successful
            // if (json_last_error() === JSON_ERROR_NONE) {
            foreach ($request['materialArray'] as $material) {
                // Create the exchange details record
                $transfer->details()->create([
                    'material_id' => $material['material_id'],
                    'qty' => $material['qty'],
                    'price' => $material['price'] * 100,
                    'total' => $material['total'] * 100,
                ]);
            }
            // } else {
            //     // Handle JSON decoding error
            //     Log::error('Error decoding JSON: ' . json_last_error_msg());
            //     throw new \Exception('Error decoding JSON: ' . json_last_error_msg(), 1);
            // };

            $this->collectMaterialMovement($transfer->load('details'));
            
            $result =  match ($transfer->transfer_type) {
                PurchasesMethod::STORES->value => Movement::storeMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->createTransferFromMovement($transfer->from_store)
                    && Movement::storeMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->createTransferToMovement($transfer->to_store),
                PurchasesMethod::SECTIONS->value => Movement::sectionMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->createTransferFromMovement($transfer->from_section)
                    && Movement::storeMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->createTransferToMovement($transfer->to_section),
                default => throw new \Exception("Un supported transfer type", 1),
            };
            dd($result);

            if ($result) {
                DB::commit();
                return $result;
            }
            return $result;
        } catch (\Throwable $e) {
            Log::error('transfer creation failed: ' . $e->getMessage(), [
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

            $data = $this->collectMaterialTransferData($request);

            if ($request['image'] != 'undefined') {
                $data['image'] = $this->storeMaterialTransferImage($request['image']);
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
            Log::error('exchange creation failed: ' . $e->getMessage(), [
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
     * storeMaterialTransferImage
     * @param  $image
     * @return string
     */
    private function storeMaterialTransferImage($image): string
    {
        $extension = $image->getClientOriginalExtension();
        $fileName = time() . '_' . rand(1000, 9999) . '.' . $extension;
        $path = 'stock/images/transfers';
        $image->move(public_path($path), $fileName);
        return asset($path . '/' . $fileName);
    }


    /**
     * collectMaterialTransferData
     * @param array $params
     * @return array
     */
    private function collectMaterialTransferData(
        array $params
    ): array {
        return [
            'transfer_type' => $params['transfer_type'],
            'serial_nr' => $params['serial_nr'],
            'transfer_date' => $params['transfer_date'],
            'user_id' => 10, #Auth::id(),
            'notes' => $params['notes'],
            'total' => $params['total'],
        ];
    }

    /**
     * collectMaterialMovement
     * @param MaterialTransfer $transfers
     * @return void
     */
    private function collectMaterialMovement(
        MaterialTransfer $transfer
    ): void {

        $transfer->details->map(function ($item) use ($transfer) {
            return [
                array_push($this->movement, [
                    'transfer_nr' => $transfer->id,
                    'material_id' => $item->material_id,
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'type' => MaterialMove::TRANSFER->value
                ])
            ];
        });
    }
}
