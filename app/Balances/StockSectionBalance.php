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
     * @param Section $section
     * @return bool
     */
    public function create(
        $section
    ): bool {
        try {

            /*
            * material move inside section
            */
            $section->move()->createMany($this->move);

            /*
            * increase balance of section
            */
            $collect = $section->getBalance();
            $collect->map(function ($items) {
                return $this->balance[] = [
                    'qty' => $items->sum('qty'),
                    'avg_price' => $items->avg('price'),
                    'material_id' => $items->first()->material_id,
                ];
            });

            foreach ($this->balance as $balance) {
                $section->balance()->updateOrCreate(
                    [
                        'section_id' => $section->id,
                        'material_id' => $balance['material_id']
                    ],
                    [
                        'qty' => $balance['qty'],
                        'avg_price' => $balance['avg_price']
                    ]
                );
            }


            return true;
        } catch (\Throwable $e) {
            Log::error('Purchase creation failed: ' . $e->getMessage(), [
                'balance' => $this->balance,
                'move' => $this->move,
                'params' => $section,
            ]);
            return false;
        }
    }

    /**
     * update
     *
     * @return bool
     */
    public function update(
        $section
    ): bool {
        try {
            /*
            * material move inside section
            */
            foreach ($this->move as $move) {
                $section->move()->updateOrCreate(
                    [
                        'material_id' => $move['material_id'],
                        'invoice_nr' => $move['invoice_nr']
                    ],
                    [
                        'qty' => $move['qty'],
                        'price' => $move['price'],
                        'type' => $move['type'],
                    ]
                );
            }


            /*
            *  balance of store
            */
            $collect = $section->getBalance();
            $collect->map(function ($items) {
                return $this->balance[] = [
                    'qty' => $items->sum('qty'),
                    'avg_price' => $items->avg('price'),
                    'material_id' => $items->first()->material_id,
                ];
            });

            foreach ($this->balance as $balance) {
                $section->balance()->updateOrCreate(
                    [
                        'store_id' => $section->id,
                        'material_id' => $balance['material_id']
                    ],
                    [
                        'qty' => $balance['qty'],
                        'avg_price' => $balance['avg_price']
                    ]
                );
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Purchase creation failed: ' . $e->getMessage(), [
                'balance' => $this->balance,
                'move' => $this->move,
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
