<?php

namespace App\Http\Controllers\Stock;

use App\Models\Item;
use App\Models\Units;
use App\Models\Branch;
use App\Models\material;
use App\Models\MainGroup;
use Illuminate\Http\Request;
use App\Models\MainComponents;
use App\Models\ComponentsItems;
use App\Models\MaterialSections;
use App\Models\Stock\StockGroup;
use App\Http\Controllers\Controller;
use App\Models\Stock\Material as StockMaterial;
use App\Http\Requests\Stock\Item\TransferItemComponentRequest;

class ComponentItemsController extends Controller
{
    public $status = false;


    public function index()
    {
        $branchs = Branch::get();
        return view('stock.Items.index', compact('branchs'));
    }
    public function saveComponent(Request $request)
    {
        if ($request->materialArray != null) {
            if (MainComponents::limit(1)->where(['branch' => $request->branch, 'item' => $request->items])->count() == 0) {
                $saveMain = MainComponents::create([
                    'branch' => $request->branch,
                    'item' => $request->items,
                    'cost' => $request->totalPrice,
                    'percentage' => $request->percentage,
                    'quantity' => $request->productQty
                ]);
            } else {
                MainComponents::limit(1)->where(['branch' => $request->branch, 'item' => $request->items])->update([
                    'cost' => $request->totalPrice,
                    'percentage' => $request->percentage,
                    'quantity' => $request->productQty
                ]);
            }
            foreach ($request->materialArray as $material) {
                if (ComponentsItems::where(['branch' => $request->branch, 'item_id' => $request->items, 'material_id' => $material['code']])->count() > 0) {
                    $save = ComponentsItems::where(['branch' => $request->branch, 'item_id' => $request->items, 'material_id' => $material['code']])
                        ->update([
                            'cost'     => $material['price'],
                            'quantity' => $material['quantity'],
                        ]);
                } else {
                    $save = ComponentsItems::create([
                        'branch'      => $request->branch,
                        'item_id'     => $request->items,
                        'material_id' => $material['code'],
                        'material_name' => $material['name'],
                        'cost'        => $material['price'],
                        'quantity'    => $material['quantity'],
                        'unit'    => $material['unit'],
                    ]);
                }
            }
            if ($save) {
                return response()->json([
                    'status' => true,
                    'data' => 'تم اضافة المكونات بنجاح'
                ]);
            }
        }
    }
    public function deleteComponent(Request $request)
    {
        $del_item  = ComponentsItems::limit(1)->where(['branch' => $request->branch, 'item_id' => $request->items, 'material_id' => $request->code])->delete();
        if ($del_item) {
            MainComponents::limit(1)->where(['branch' => $request->branch, 'item' => $request->items])->update([
                'cost' => $request->totalPrice,
                'percentage' => $request->percentage
            ]);
        }
        return response()->json([
            'status' => true,
            'data' => 'تم حذف المكون بنجاح'
        ]);
    }
    public function get_material_in_item(Request $request)
    {
        $materials = MainComponents::with('Materials')->where(['branch' => $request->branch, 'item' => $request->item])->first();
        return response()->json([
            'status' => true,
            'materials' => $materials
        ]);
    }


    public function transfer_material(
        TransferItemComponentRequest $request
    ) {
        foreach ($request->validated()['item_id'] as $key => $item) {

            $chekMain = false;
            $sum = 0;
            $main = array();
            $addItem = array();
            foreach ($request->components as $material) {
                if (MainComponents::limit(1)->where(['branch' => $request->validated()['branch'], 'item' =>  $item])->count() == 0) {
                    $chekMain = true;
                }
                $addItem['branch'] = $request->validated()['branch'];
                $addItem['item'] = $item;
                $addItem['quantity'] = 1;

                if (ComponentsItems::limit(1)->where(['branch' => $request->validated()['branch'], 'item_id' =>  $item, 'material_id' => $material['material_id']])->count() == 0) {
                    $main['branch'] = $request->validated()['branch'];
                    $main['item_id'] = $item;
                    $main['material_id'] = $material['material_id'];
                    $main['material_name'] = $material['material_name'];
                    $main['quantity'] = $material['quantity'];
                    $main['cost'] = $material['cost'];
                    $main['unit'] = $material['unit'];
                    $sum += $material['cost'];
                    $addMaterial = ComponentsItems::create($main);
                }
            }
            $getItem = Item::limit(1)->where(['branch_id' => $addItem['branch'], 'id' => $addItem['item']])->select(['price'])->first();
            $addItem['cost'] = $sum;
            $addItem['percentage'] = number_format($sum / $getItem->price * 100, 2, '.', '');
            if ($chekMain) {
                $addMain = MainComponents::create($addItem);
            } else {
                $main_cost = MainComponents::limit(1)->where(['branch' => $request->validated()['branch'], 'item' =>  $item])->select(['cost', 'percentage', 'quantity'])->first();
                MainComponents::limit(1)->where(['branch' => $request->validated()['branch'], 'item' =>  $item])->update([
                    'cost' => $addItem['cost'] + $main_cost->cost,
                    'percentage' => $addItem['percentage'] + $main_cost->percentage,
                ]);
            }
        }
        return ['status' => true, 'data' => 'تم تكرار المكونات بنجاح'];
    }

    public function printComponents(Request $request)
    {
        $counter = 0;
        $conGroups = [];
        if ($request->branch) {
            if ($request->details == 1) {
                $data = Item::with('custom_materials.Materials', 'getdetails.details', 'getdetails.materials', 'getdetails.materials.materials')->where(['branch_id' => $request->branch])->select(['id', 'name', 'group_id', 'cost_price'])->get();
            } else {
                $data = Item::with('custom_materials.Materials')->where(['branch_id' => $request->branch])->select(['id', 'name', 'group_id', 'cost_price'])->get();
            }
            $this->status = true;
        } else {
            $data = "select branch please";
        }
        return ['status' => $this->status, 'data' => $data];
    }
    public function printItems(Request $request)
    {
        if ($request->items) {
            $data = Item::with('material_components')->where(['branch_id' => $request->branch, 'id' => $request->items])->select(['id', 'name'])->get();
            $this->status = true;
        } else {
            $data = "select item please";
        }
        return ['status' => $this->status, 'data' => $data];
    }
    public function printComponent(Request $request)
    {
        if (!$request->branch) {
            $data = 'select branch please';
        }
        if (!$request->materials) {
            $data = 'select material please';
        }
        if ($request->branch && $request->materials) {
            $this->status = true;
            $data = StockMaterial::limit(1)->with(['components' => function ($query) {
                $query->select(['item_id', 'quantity', 'cost', 'material_id']);
            }, 'components.item'])->where(['code' => $request->materials])->select(['name', 'code'])->first();
        }
        return ['status' => $this->status, 'data' => $data];
    }
    public function componentWithoutItems(Request $request)
    {
        $data = [];
        $counter = 0;
        if ($request->branch) {
            $branch = $request->branch;
            $material = material::with(['components' => function ($query) use ($branch) {
                $query->where('branch', $branch);
            }])->get();
            foreach ($material as $ma) {
                $con = sizeof($ma->components);
                if ($con == 0) {
                    $data[$counter]['material'] = $ma->name;
                    $data[$counter]['code'] = $ma->code;
                    $counter++;
                }
            }
            $this->status = true;
        } else {
            $this->status = false;
            $data = 'select branch please';
        }
        return ['status' => $this->status, 'data' => $data];
    }
}
