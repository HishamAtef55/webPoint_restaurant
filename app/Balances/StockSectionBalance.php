<?php

namespace App\Balances;

use App\Models\Stock\Store;
use App\Models\Stock\Purchases;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Stock\SectionBalance;
use App\Balances\Abstract\BalanceAbstract;
use App\Balances\Interface\BalanceInterface;
use App\Models\Stock\Material;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Stock\Section;

class StockSectionBalance extends BalanceAbstract implements BalanceInterface
{

    protected Section $model;

    /**
     * create
     * @param Section $section
     * @return bool
     */
    public function purchasesBalance(
        $section
    ): bool {
        try {

            if (!$this->balance) return false;
            /*
            * increase balance of section
            */
            foreach ($this->balance as $balance) {
                $oldBalance = $section->balance()->where('material_id', $balance['material_id'])->first();
                if ($oldBalance) {
                    $price = ($oldBalance->avg_price + $balance['price']) /  ($oldBalance->qty  + $balance['qty']);
                    $qty = $oldBalance->qty += $balance['qty'];
                    $oldBalance->update([
                        'qty' => $qty,
                        'avg_price' => $price,
                    ]);
                } else {
                    $section->balance()->create(
                        [
                            'store_id' => $section->id,
                            'material_id' => $balance['material_id'],
                            'qty' =>  $balance['qty'],
                            'avg_price' => $balance['price'],
                        ]
                    );
                }
            }


            return true;
        } catch (\Throwable $e) {
            Log::error('increase store balance creation failed: ' . $e->getMessage(), [
                'balance' => $this->balance,
                'params' => $section,
            ]);
            return false;
        }
    }

    /**
     * create
     * @param Material $material
     * @param int $id
     * @return int
     */
    public function currentBalanceByMaterial(
        Material $material,
        int $id
    ): int {
        $this->model = Section::findOrFail($id);
        $balance = $this->model->balance()->whereBelongsTo($material)->first()->qty ?? 0;
        return $balance;
    }
}
