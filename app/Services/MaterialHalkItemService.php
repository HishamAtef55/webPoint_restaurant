<?php

namespace App\Services;

use App\Enums\MaterialMove;
use Illuminate\Http\Request;
use App\Enums\PurchasesMethod;
use App\Models\Stock\Exchange;
use App\Models\Stock\MaterialHalk;
use App\Models\Stock\MaterialHalkItem;
use Illuminate\Support\Facades\DB;
use App\Movements\Facades\Movement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MaterialHalkItemService
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

            /*
            * collectHalkItemeData
            */

            $data = $this->collectHalkItemeData($params);


            DB::beginTransaction();

            /*
            * store new halk item
            */

            $item_halk = MaterialHalkItem::create($data);

            /*
            * store the material halk item details record
            */
            $item_halk->details()->createMany($params['items']);

            /*
            * collectMaterialMovement
            */

            $this->collectMaterialMovement($item_halk->load('details'));

            /*
            * material halk item movement
            */

            $result = Movement::sectionMaterialMovement()->validate(MaterialMove::HALKITEM->value, $this->movement)->create($item_halk->section);

            if ($result) {
                DB::commit();
                return $result;
            }
            return $result;
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('material halk item creation failed: ' . $e->getMessage(), [
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
     * @param MaterialHalkItem  $item_halk,
     * @return bool
     */
    public function update(
        array $params,
        $item_halk,
    ): bool {
        try {

            /*
            * collectHalkItemeData
            */

            $data = $this->collectHalkItemeData($params);


            DB::beginTransaction();

            /*
            * update halk item
            */
            $item_halk->update($data);


            foreach ($params['items'] as $item) {
                /*
                * store the material halk item details record
                */
                $item_halk->details()->updateOrCreate(
                    [
                        'item_id' => $item['item_id'],
                    ],
                    [
                        'qty' => $item['qty'],
                    ]
                );
            }

            /*
            * collectMaterialMovement
            */

            $this->collectMaterialMovement($item_halk->load('details'));

            /*
            * material halk item movement
            */

            $result = Movement::sectionMaterialMovement()->validate(MaterialMove::HALKITEM->value, $this->movement)->create($item_halk->section);

            if ($result) {
                DB::commit();
                return $result;
            }
            return $result;
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('material halk item updating failed: ' . $e->getMessage(), [
                'data' => $data,
                'params' => $params,
            ]);
            DB::rollBack();
            return false;
        }
    }


    /**
     * delete  
     * @param  MaterialHalkItem $item_halk,
     * @param  int $id
     * @return bool
     */
    public function delete(
        MaterialHalkItem $item_halk,
        int $id
    ): bool {

        try {

            DB::beginTransaction();

            if (!$item_halk->hasDetails()) return false;

            $details = $item_halk->details()->find($id);

            if (!$details) {
                throw ValidationException::withMessages([
                    'error' => 'the material halk details not found'
                ]);
            }

            /*
            * delete material halk item movement
            */

            $result = Movement::sectionMaterialMovement()->validate(MaterialMove::HALKITEM->value, $this->movement)->deleteHalkItemMovement($item_halk, $details);

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
            Log::error('halk item details deleting failed: ' . $e->getMessage(), [
                'item_halk' => $item_halk,
            ]);
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * collectHalkItemeData
     * @param array $params
     * @return array
     */
    private function collectHalkItemeData(
        array $params
    ): array {
        return [
            'serial_nr' => $params['serial_nr'],
            'halk_item_date' => $params['halk_item_date'],
            'notes' => $params['notes'],
            'user_id' => 10, #Auth::id(),
            'section_id' => $params['section_id'],
        ];
    }

    /**
     * collectMaterialMovement
     * @param MaterialHalk $material_halk_item
     * @return void
     */
    private function collectMaterialMovement(
        MaterialHalkItem $material_halk_item
    ): void {

        $material_halk_item->details->map(function ($item) use ($material_halk_item) {
            $item->components->map(function ($component) use ($material_halk_item, $item) {
                return [
                    array_push($this->movement, [
                        'halk_item_nr' => $material_halk_item->id,
                        'material_id'  => $component->material_id,
                        'qty'          => $item->qty,
                        'price'        => (int)($component->cost * 100),
                        'type'         => MaterialMove::HALKITEM->value
                    ])
                ];
            });
        });
    }
}
