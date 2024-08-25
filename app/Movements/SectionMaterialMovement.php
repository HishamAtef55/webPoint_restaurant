<?php

namespace App\Movements;

use App\Models\Stock\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Stock\SectionMaterialMove;
use App\Movements\Abstract\MovementAbstract;
use Illuminate\Database\Eloquent\Collection;
use App\Movements\Interface\MovementInterface;

class SectionMaterialMovement extends MovementAbstract implements MovementInterface
{

    protected SectionMaterialMove $model;

    /**
     * create
     * @param Store $store
     * @return bool
     */
    public function create(
        $store,
    ): bool {
        try {
            dd($this->type);
            DB::beginTransaction();

            $store->move()->createMany($this->movement);



            DB::commit();

            return true;
        } catch (\Throwable $e) {
            Log::error('Movement creation failed: ' . $e->getMessage(), [
                'movement' => $this->movement,
                'store' => $store,
            ]);
            return false;
        }
    }
}
