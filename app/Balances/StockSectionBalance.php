<?php

namespace App\Balances;

use App\Models\Stock\Store;
use App\Models\Stock\Purchases;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Stock\SectionBalance;
use App\Balances\Abstract\BalanceAbstract;
use App\Balances\Interface\BalanceInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Stock\Section;

class StockSectionBalance extends BalanceAbstract implements BalanceInterface
{

    protected Section $model;

    /**
     * create
     * @param Store $store
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
                $updateOldBalance = $section->balance()->where('material_id', $balance['material_id'])->first();
                if ($updateOldBalance) {
                    $price = ($updateOldBalance->avg_price + $balance['price']) /  ($updateOldBalance->qty  + $balance['qty']);
                    $updateOldBalance->update([
                        'qty' => $updateOldBalance->qty += $balance['qty'],
                        'avg_price' => $price,
                    ]);
                } else {
                    $section->balance()->create(
                        [
                            'section_id' => $section->id,
                            'material_id' => $balance['material_id'],
                            'qty' =>  $balance['qty'],
                            'avg_price' => $balance['price'],
                        ]
                    );
                }
            }
            return true;
        } catch (\Throwable $e) {
            Log::error('increase section balance creation failed: ' . $e->getMessage(), [
                'balance' => $this->balance,
                'params' => $section,
            ]);
            return false;
        }
    }

    /**
     * delete
     * @param Purchases $purchases
     * @param int $id
     * @return bool
     */
    public function delete(
        Purchases $purchases,
        int $id
    ): bool {

        try {

            DB::beginTransaction();
            /*
            *  find purchase item
            */
            $details = $purchases->details()->find($id);

            $this->model = $purchases->store;

            /*
            *  store delete move
            */
            $this->model->move()->where([
                'invoice_nr' => $purchases->serial_nr,
                'material_id' => $details->material_id
            ])->delete();

            /*
            *  update store balance
            */
            $this->model->balance()->where([
                'material_id' => $details->material_id
            ])->update([
                'qty' => DB::raw('qty - ' . $details->qty),
                'avg_price' => DB::raw('avg_price - ' . $details->price)
            ]);

            /*
            *  delete purchase item
            */
            $details->delete();

            DB::commit();

            return true;
        } catch (\Throwable $e) {
            Log::error('Purchase details deleted failed: ' . $e->getMessage(), [
                'purchases' => $purchases,
                'id' => $id,
            ]);
            return false;
        }
    }
}
