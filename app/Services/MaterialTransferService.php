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
     * @return bool
     */
    public function store(
        array $request
    ): bool {

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

            $request['materialArray'] = json_decode($request['materialArray'], true);

            //  Check if decoding was successful
            if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($request['materialArray'] as $material) {
                    // Create the transfer details record
                    $transfer->details()->create([
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

            $this->collectMaterialMovement($transfer->load('details'));

            $result =  match ($transfer->transfer_type) {
                PurchasesMethod::STORES->value => Movement::storeMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->createTransferFromMovement($transfer->from_store)
                    && Movement::storeMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->createTransferToMovement($transfer->to_store),
                PurchasesMethod::SECTIONS->value => Movement::sectionMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->createTransferFromMovement($transfer->from_section)
                    && Movement::sectionMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->createTransferToMovement($transfer->to_section),
                default => throw new \Exception("Un supported transfer type", 1),
            };

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
     * @param MaterialTransfer  $transfer,
     * @return bool
     */
    public function update(
        array $request,
        MaterialTransfer  $transfer,
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

            $transfer->update($data);
            $request['materialArray'] = json_decode($request['materialArray'], true);

            // Check if decoding was successful
            if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($request['materialArray'] as $material) {
                    // Create the transfer details record
                    $transfer->details()->updateOrCreate(
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

            $this->collectMaterialMovement($transfer->load('details'));

            $result =  match ($transfer->transfer_type) {
                PurchasesMethod::STORES->value => Movement::storeMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->createTransferFromMovement($transfer->from_store)
                    && Movement::storeMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->createTransferToMovement($transfer->to_store),
                PurchasesMethod::SECTIONS->value => Movement::sectionMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->createTransferFromMovement($transfer->from_section)
                    && Movement::sectionMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->createTransferToMovement($transfer->to_section),
                default => throw new \Exception("Un supported transfer type", 1),
            };

            if ($result) {
                DB::commit();
                return $result;
            }
            return $result;
        } catch (\Throwable $e) {
            Log::error('exchange creation failed: ' . $e->getMessage(), [
                'request' => $request,
            ]);
            return false;
        }
    }

    /**
     * delete  
     * @param  MaterialTransfer  $transfer
     * @param  int $id
     * @return bool
     */
    public function delete(
        MaterialTransfer  $transfer,
        int $id
    ): bool {

        try {

            DB::beginTransaction();

            if (!$transfer->hasDetails()) return false;

            $details = $transfer->details()->find($id);

            if (!$details) {
                throw ValidationException::withMessages([
                    'error' => 'the exchange details not found'
                ]);
            }

            $result =  match ($transfer->transfer_type) {
                PurchasesMethod::STORES->value => Movement::storeMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->deleteTransferFromMovement($transfer, $details)
                    && Movement::storeMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->deleteTransferToMovement($transfer, $details),
                PurchasesMethod::SECTIONS->value => Movement::sectionMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->deleteTransferFromMovement($transfer, $details)
                    && Movement::sectionMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->deleteTransferToMovement($transfer, $details),
                default => throw new \Exception("Un supported transfer type", 1),
            };

            if ($result) {

                /*
                *  delete exchange item
                */
                $details->delete();

                DB::commit();

                return $result;
            }
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('transfer details deleting failed: ' . $e->getMessage(), [
                'transfer' => $transfer,
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
            'user_id' => Auth::id(),
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
