<?php

namespace App\Services;

use App\Enums\MaterialMove;
use Illuminate\Http\Request;
use App\Enums\PurchasesMethod;
use App\Models\Stock\Exchange;
use App\Models\Stock\MaterialHalk;
use Illuminate\Support\Facades\DB;
use App\Movements\Facades\Movement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MaterialHalkService
{

    /*
     * The attributes that hold material move.
     *
     * @var array<int, string>
    */
    protected $movement = [];

    /**
     * create
     * @param array $params
     * @return bool
     */
    public function create(
        array $params
    ): bool {
        try {

            $data = $this->collectInvoiceData($params);

            if ($params['image'] != 'undefined') {
                $data['image'] = $this->storeInvoiceImage($params['image']);
            }

            if ($params['halk_type'] === PurchasesMethod::STORES->value) {
                $data['store_id'] = $params['store_id'];
            } elseif ($params['halk_type'] === PurchasesMethod::SECTIONS->value) {
                $data['section_id'] = $params['section_id'];
            }

            DB::beginTransaction();

            /*
            * store new invoice
            */

            $material_halk = MaterialHalk::create($data);

            $params['materialArray'] = json_decode($params['materialArray'], true);

            // // Check if decoding was successful
            if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($params['materialArray'] as $material) {
                    // Create the material halk details record
                    $material_halk->details()->create([
                        'material_id' => $material['material_id'],
                        'qty'         => $material['qty'],
                        'price'       => $material['price'] * 100,
                        'total'       => $material['total'] * 100,
                    ]);
                }
            } else {
                // Handle JSON decoding error
                Log::error('Error decoding JSON: ' . json_last_error_msg());
                throw new \Exception('Error decoding JSON: ' . json_last_error_msg(), 1);
            };

            $this->collectMaterialMovement($material_halk->load('details'));

            $result =  match ($material_halk->halk_type) {
                PurchasesMethod::STORES->value => Movement::storeMaterialMovement()->validate(MaterialMove::HALK->value, $this->movement)->create($material_halk->store),
                PurchasesMethod::SECTIONS->value => Movement::sectionMaterialMovement()->validate(MaterialMove::HALK->value, $this->movement)->create($material_halk->section),
                default => throw new \Exception("Un supported halk type", 1),
            };
            if ($result) {
                DB::commit();
                return $result;
            }
            return $result;
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('material halk creation failed: ' . $e->getMessage(), [
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
     * @param $material_halk,
     * @return bool
     */
    public function update(
        array $params,
        $material_halk,
    ): bool {
        try {

            $data = $this->collectInvoiceData($params);

            if ($params['image'] != 'undefined') {
                $data['image'] = $this->storeInvoiceImage($params['image']);
            }

            if ($params['halk_type'] === PurchasesMethod::STORES->value) {
                $data['store_id'] = $params['store_id'];
            } elseif ($params['halk_type'] === PurchasesMethod::SECTIONS->value) {
                $data['section_id'] = $params['section_id'];
            }

            DB::beginTransaction();

            /*
            * update invoice
            */
            $material_halk->update($data);

            $params['materialArray'] = json_decode($params['materialArray'], true);

            // Check if decoding was successful
            if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($params['materialArray'] as $material) {
                    // Create the purchase details record
                    $material_halk->details()->updateOrCreate(
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
            }

            $this->collectMaterialMovement($material_halk->load('details'));
            $result =  match ($material_halk->halk_type) {
                PurchasesMethod::STORES->value => Movement::storeMaterialMovement()->validate(MaterialMove::HALK->value, $this->movement)->create($material_halk->store),
                PurchasesMethod::SECTIONS->value => Movement::sectionMaterialMovement()->validate(MaterialMove::HALK->value, $this->movement)->create($material_halk->section),
                default => throw new \Exception("Un supported halk type", 1),
            };
            if ($result) {
                DB::commit();
                return $result;
            }
            return $result;
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('material halk updated failed: ' . $e->getMessage(), [
                'data' => $data,
                'params' => $params,
            ]);
            DB::rollBack();
            throw $e;
        }
    }


    /**
     * delete  
     * @param  MaterialHalk $material_halk,
     * @param  int $id
     * @return bool
     */
    public function delete(
        MaterialHalk $material_halk,
        int $id
    ): bool {

        try {

            DB::beginTransaction();

            if (!$material_halk->hasDetails()) return false;

            $details = $material_halk->details()->find($id);

            if (!$details) {
                throw ValidationException::withMessages([
                    'error' => 'the exchange details not found'
                ]);
            }

            $result =  match ($material_halk->halk_type) {
                PurchasesMethod::STORES->value => Movement::storeMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->deleteHalkMovement($material_halk, $details),
                PurchasesMethod::SECTIONS->value => Movement::sectionMaterialMovement()->validate(MaterialMove::TRANSFER->value, $this->movement)->deleteHalkMovement($material_halk, $details),
                default => throw new \Exception("Un supported halk type", 1),
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
            Log::error('halk details deleting failed: ' . $e->getMessage(), [
                'material_halk' => $material_halk,
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
        $path = 'stock/images/materials/halk';
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
            'halk_date' => $params['halk_date'],
            'halk_type' => $params['halk_type'],
            'notes' => $params['notes'],
            'user_id' => Auth::id(),
            'total' => $params['total'] * 100,
        ];
    }

    /**
     * collectMaterialMovement
     * @param MaterialHalk $material_halk
     * @return void
     */
    private function collectMaterialMovement(
        MaterialHalk $material_halk
    ): void {

        $material_halk->details->map(function ($item) use ($material_halk) {
            return [
                array_push($this->movement, [
                    'halk_nr' => $material_halk->id,
                    'material_id' => $item->material_id,
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'type' => MaterialMove::HALK->value
                ])
            ];
        });
    }
}
