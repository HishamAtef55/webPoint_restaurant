<?php

namespace App\Http\Controllers\Stock;

use App\Enums\Unit;
use App\Models\Branch;
use App\Models\Stores;
use App\Models\storeCost;
use App\Models\Suppliers;
use App\Models\stock_unit;
use App\Models\materialLog;
use App\Models\sectionCost;
use App\Models\Stock\Store;
use App\Models\stocksection;
use App\Traits\MainFunction;
use Illuminate\Http\Request;
use App\Models\Stock\Section;
use App\Models\Stock\Material;
use App\Models\Stock\Supplier;
use App\Models\storePurchases;
use App\Models\sectionPurchases;
use App\Models\operationsDetails;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\storePurchasesDetails;
use App\Models\sectionPurchasesDetails;

class PurchasesController extends Controller
{
    use MainFunction;
    public function __construct()
    {
        $this->middleware('auth');
    }
    protected function getSerial($type)
    {
        $serial = 0;
        if ($type == 'store') {
            $statement  = DB::select("SHOW TABLE STATUS LIKE 'stock_purchases'");
            $serial = $statement[0]->Auto_increment;
        } elseif ($type == 'section') {
            $statement  = DB::select("SHOW TABLE STATUS LIKE 'stock_section_purchases'");
            $serial = $statement[0]->Auto_increment;
        }
        return $serial;
    }

    public function index()
    {
        $serial = $this->getSerial('store');
        $stores    = Store::get();
        $branches = Branch::get();
        $suppliers = Supplier::get();
        $materials = Material::get();
        $materials->map(function ($material) {
            $material->unit = Unit::view($material->unit);
        });
        return view('stock.stock.purchases', compact('serial', 'stores', 'branches', 'suppliers', 'materials'));
    }
    public function changeType(Request $request)
    {
        return ['serial' => $this->getSerial($request->type)];
    }
    public function changeBranch(Request $request)
    {
        $sections = Section::where(['branch_id' => $request->branch])->get();
        return ['sections' => $sections];
    }
    public function changeSection(Request $request)
    {
        $materials = sectionCost::where(['section_id' => $request->section])->get();
        return ['materials' => $materials];
    }
    public function changeStore(Request $request)
    {
        $materials = storeCost::with('sub_unit', 'sub_unit.sub_unit')->where(['store_id' => $request->store])->get();
        return ['materials' => $materials];
    }
    public function getUnit(Request $request)
    {
        $units = [];
        $counter = 0;
        $materials = [];
        $min = 0;
        $max = 0;
        if (!isset($request->type)) {
            $request->type = 'store';
        }
        if ($request->type == 'store') {
            $materials = storeCost::limit(1)->with('sub_unit', 'sub_unit.sub_unit', 'MainMaterial')->where(['id' => $request->id])->first();
            $min = $materials->MainMaterial->min_store;
            $max = $materials->MainMaterial->max_store;
        } elseif ($request->type == 'section') {
            $materials = sectionCost::limit(1)->with('sub_unit', 'sub_unit.sub_unit', 'MainMaterial')->where(['id' => $request->id])->first();
            $min = $materials->MainMaterial->min_section;
            $max = $materials->MainMaterial->max_section;
        }
        $units[$counter]['name'] = $materials->sub_unit->name;
        $units[$counter]['size'] = $materials->sub_unit->size;
        $counter++;
        $units[$counter]['name'] = $materials->sub_unit->sub_unit->name;
        $units[$counter]['size'] = $materials->sub_unit->sub_unit->size;
        return ['units' => $units, 'last_price' => $materials->l_price, 'qty' => $materials->qty, 'ava' => $materials->average, 'max' => $max, 'min' => $min];
    }
    protected function storeImage($image)
    {
        $file = $image->getClientOriginalExtension();
        $no_rand = rand(10, 1000);
        $file_name =  time() . $no_rand .  '.' . $file;
        $image->move('stock/images/purchases', $file_name);
        return asset('stock/images/purchases') . '/' . $file_name;
    }
    public function save(Request $request)
    {
        $data = json_decode($request->materialArray);
        $rowId = 0;
        $image = '';
        if ($request->image != 'undefined') {
            $image = $this->storeImage($request->image);
        }
        if ($request->type == "store") {
            $save = storePurchases::create([
                'serial' => $request->seriesNumber,
                'note' => $request->notes,
                'store_id' => $request->stores,
                'date' => $request->date,
                'user' => Auth::user()->id,
                'image' => $image,
                'type' => $request->payType,
                'supplier' => $request->supplier,
                'sub_total' => $request->sumTotal,
                'tax' => $request->tax,
                'tax_value' => $request->sumTax,
                'discount' => $request->sumDiscount,
                'total' => $request->sumFinal,
            ]);
            if ($save) {
                foreach ($data as $row) {
                    $saveDeatils = storePurchasesDetails::create([
                        'order_id' => $save->id,
                        'code' => $row->code,
                        'name' => $row->itemName,
                        'expire' => $row->Expire,
                        'unit' => $row->unitName,
                        'qty' => $row->quantity,
                        'price' => $row->priceUnit,
                        'sub_total' => $row->totalUnit,
                        'tax' => $row->taxPrice,
                        'discount' => $row->discountPrice,
                        'total' => $row->finalTotal,
                    ]);
                    if ($saveDeatils) {
                        $updateCost = storeCost::limit(1)->where(['store_id' => $request->stores, 'code' => $row->code])->first();
                        $realPrice = $row->priceUnit;
                        if ($row->unitName == $updateCost->unit) {
                            if ($updateCost->f_price == 0) {
                                $updateCost->f_price = $realPrice;
                            }
                            $updateCost->l_price = $realPrice;
                            $avaPrice = (($updateCost->qty * $updateCost->average) + ($realPrice * $row->quantity)) / ($updateCost->qty + $row->quantity);
                            $updateCost->average = $avaPrice;
                            $updateCost->qty += $row->quantity;
                        } else {
                            $unitQty = stock_unit::limit(1)->where(['name' => $updateCost->unit])->select(['size'])->first();
                            $qtyUnit = $row->quantity / $unitQty->size;
                            $priceUnit = $realPrice * $unitQty->size;
                            if ($updateCost->f_price == 0) {
                                $updateCost->f_price = $priceUnit;
                            }
                            $updateCost->l_price = $priceUnit;
                            $avaPrice = (($updateCost->qty * $updateCost->average) + ($priceUnit * $qtyUnit)) / ($updateCost->qty + $qtyUnit);
                            $updateCost->average = $avaPrice;
                            $updateCost->qty += $qtyUnit;
                        }
                        $updateCost->save();
                        $materialLog = [
                            'user' => Auth::user()->id,
                            'code' => $row->code,
                            'type' => 'مشتريات',
                            'qty'     => $row->quantity,
                            'unit'    => $row->unitName,
                            'price'   => $row->priceUnit,
                            'invoice_id' => $save->id,
                            'store' => $request->stores,
                        ];
                        $this->materialLog($materialLog);
                        $this->changeMaterialCost($row->code, $realPrice);
                    }
                }
                return ['status' => true, 'data' => 'تم اضافة الفاتورة بنجاح', 'id' => $this->getSerial('store')];
            }
        } elseif ($request->type == "section") {
            $save = sectionPurchases::create([
                'serial' => $request->seriesNumber,
                'note' => $request->notes,
                'branch_id' => $request->branch,
                'section_id' => $request->sections,
                'date' => $request->date,
                'user' => Auth::user()->id,
                'image' => $image,
                'type' => $request->payType,
                'supplier' => $request->supplier,
                'sub_total' => $request->sumTotal,
                'tax' => $request->tax,
                'tax_value' => $request->sumTax,
                'discount' => $request->sumDiscount,
                'total' => $request->sumFinal,
            ]);
            if ($save) {
                foreach ($data as $row) {
                    $saveDeatils = sectionPurchasesDetails::create([
                        'order_id' => $save->id,
                        'code' => $row->code,
                        'name' => $row->itemName,
                        'expire' => $row->Expire,
                        'unit' => $row->unitName,
                        'qty' => $row->quantity,
                        'price' => $row->priceUnit,
                        'sub_total' => $row->totalUnit,
                        'tax' => $row->taxPrice,
                        'discount' => $row->discountPrice,
                        'total' => $row->finalTotal,
                    ]);
                    if ($saveDeatils) {
                        $updateCost = sectionCost::limit(1)->where(['section_id' => $request->sections, 'code' => $row->code])->first();
                        $realPrice = $row->priceUnit;
                        if ($row->unitName == $updateCost->unit) {
                            if ($updateCost->f_price == 0) {
                                $updateCost->f_price = $realPrice;
                            }
                            $updateCost->l_price = $realPrice;
                            $avaPrice = (($updateCost->qty * $updateCost->average) + ($realPrice * $row->quantity)) / ($updateCost->qty + $row->quantity);
                            $updateCost->average = $avaPrice;
                            $updateCost->qty += $row->quantity;
                        } else {
                            $unitQty = stock_unit::limit(1)->where(['name' => $updateCost->unit])->select(['size'])->first();
                            $qtyUnit = $row->quantity / $unitQty->size;
                            $priceUnit = $realPrice * $unitQty->size;
                            if ($updateCost->f_price == 0) {
                                $updateCost->f_price = $priceUnit;
                            }
                            $updateCost->l_price = $priceUnit;
                            $avaPrice = (($updateCost->qty * $updateCost->average) + ($priceUnit * $qtyUnit)) / ($updateCost->qty + $qtyUnit);
                            $updateCost->average = $avaPrice;
                            $updateCost->qty += $qtyUnit;
                        }
                        $updateCost->save();
                        $materialLog = [
                            'user'    => Auth::user()->id,
                            'code'    => $row->code,
                            'type'    => 'مشتريات',
                            'qty'     => $row->quantity,
                            'unit'    => $row->unitName,
                            'price'   => $row->priceUnit,
                            'invoice_id' => $save->id,
                            'section' => $request->sections,
                        ];
                        $this->materialLog($materialLog);
                        $this->changeMaterialCost($row->code, $realPrice);
                    }
                }
                return ['status' => true, 'data' => 'تم اضافة الفاتورة بنجاح', 'id' => $this->getSerial('store')];
            }
        }
        return $data;
    }
    protected function materialLog($data)
    {
        materialLog::create($data);
    }
    public function getPurchase(Request $request)
    {
        $data = [];
        if ($request->type == 'store') {
            $data = storePurchases::limit(1)->with('details')->where(['id' => $request->permission])->first();
        } elseif ($request->type == 'section') {
            $data = sectionPurchases::limit(1)->with('details')->where(['id' => $request->permission])->first();
        }
        return ['status' => true, 'data' => $data];
    }
    public function getPurchaseViaSerial(Request $request)
    {
        $data = [];
        if ($request->type == 'store') {
            $data = storePurchases::limit(1)->with('details')->where(['serial' => $request->serial])->first();
        } elseif ($request->type == 'section') {
            $data = sectionPurchases::limit(1)->with('details')->where(['serial' => $request->serial])->first();
        }
        return ['status' => true, 'data' => $data];
    }
    public function deletePurchase(Request $request)
    {
        $flag = false;
        if ($request->type == 'store') {
            $getPurchase = storePurchases::find($request->permission);
            $getPurchaseDetails = storePurchasesDetails::where(['order_id' => $request->permission])->get();
            foreach ($getPurchaseDetails as $row) {
                $updateQty = storeCost::limit(1)->where(['store_id' => $getPurchase->store_id, 'code' => $row->code])->first();
                $updateQty->qty -= $row->qty;
                $$updateQty->save();
                $deleteDetails = storePurchasesDetails::find($row->id);
                $deleteDetails->delete();
            }
            if ($getPurchaseDetails) {
                $getPurchase->delete();
                $flag = true;
            }
        } elseif ($request->type == 'section') {
            $getPurchase = sectionPurchases::find($request->permission);
            $getPurchaseDetails = sectionPurchasesDetails::where(['order_id' => $request->permission])->get();
            foreach ($getPurchaseDetails as $row) {
                $updateQty = sectionCost::limit(1)->where(['section_id' => $getPurchase->section_id, 'code' => $row->code])->first();
                $updateQty->qty -= $row->qty;
                $updateQty->save();
                $deleteDetails = sectionPurchasesDetails::find($row->id);
                $deleteDetails->delete();
            }
            if ($getPurchaseDetails) {
                $getPurchase->delete();
                $flag = true;
            }
        }
        if ($flag) {
            return ['status' => true, 'data' => 'تم حذف الفاتورة بنجاح'];
        }
    }
    public function deleteItemPurchase(Request $request)
    {
        $flag = false;
        if ($request->type == 'store') {
            $row  = storePurchasesDetails::limit(1)->where(['id' => $request->rowId])->first();
            $oldQty = $row->qty;
            $row->delete();
            if ($row) {
                $updatePurchase = storePurchases::find($request->permission);
                $updatePurchase->sub_total = $request->sumTotal;
                $updatePurchase->tax_value = $request->sumTax;
                $updatePurchase->discount = $request->sumDiscount;
                $updatePurchase->total = $request->sumFinal;
                $updatePurchase->save();
                if ($updatePurchase) {
                    $updateCost = storeCost::limit(1)->where(['store_id' => $updatePurchase->store_id, 'code' => $request->code])->first();
                    $updateCost->qty -= $oldQty;
                    $updateCost->save();
                    if ($updateCost) {
                        $flag = true;
                    }
                }
            }
        } elseif ($request->type == 'section') {
            $row  = sectionPurchasesDetails::limit(1)->where(['id' => $request->rowId])->first();
            $oldQty = $row->qty;
            $row->delete();
            if ($row) {
                $updatePurchase = sectionPurchases::find($request->permission);
                $updatePurchase->sub_total = $request->sumTotal;
                $updatePurchase->tax_value = $request->sumTax;
                $updatePurchase->discount = $request->sumDiscount;
                $updatePurchase->total = $request->sumFinal;
                $updatePurchase->save();
                if ($updatePurchase) {
                    $updateCost = sectionCost::limit(1)->where(['section_id' => $updatePurchase->section_id, 'code' => $request->code])->first();
                    $updateCost->qty -= $oldQty;
                    $updateCost->save();
                    if ($updateCost) {
                        $flag = true;
                    }
                }
            }
        }
        if ($flag) {
            return ['status' => true, 'data' => 'تم الحذف بنجاح'];
        }
    }
    public function updateItemPurchase(Request $request)
    {
        $flag = false;
        if ($request->type == 'store') {
            $row  = storePurchasesDetails::limit(1)->where(['id' => $request->rowId])->first();
            $oldQty = $row->qty;
            $row->price = $request->priceUnit;
            $row->qty = $request->quantity;
            $row->sub_total = $request->totalUnit;
            $row->tax = $request->taxPrice;
            $row->discount = $request->discountPrice;
            $row->total = $request->finalTotal;
            $row->save();
            if ($row) {
                $updatePurchase = storePurchases::find($request->permission);
                $updatePurchase->sub_total = $request->sumTotal;
                $updatePurchase->tax_value = $request->sumTax;
                $updatePurchase->discount = $request->sumDiscount;
                $updatePurchase->total = $request->sumFinal;
                $updatePurchase->save();
                if ($updatePurchase) {
                    $updateCost = storeCost::limit(1)->where(['store_id' => $updatePurchase->store_id, 'code' => $request->code])->first();
                    $updateCost->qty -= $oldQty;
                    $updateCost->qty += $request->quantity;
                    $updateCost->save();
                    if ($updateCost) {
                        $flag = true;
                    }
                }
            }
        } elseif ($request->type == 'section') {
            $row  = sectionPurchasesDetails::limit(1)->where(['id' => $request->rowId])->first();
            $oldQty = $row->qty;
            $row->price = $request->priceUnit;
            $row->qty = $request->quantity;
            $row->sub_total = $request->totalUnit;
            $row->tax = $request->taxPrice;
            $row->discount = $request->discountPrice;
            $row->total = $request->finalTotal;
            $row->save();
            if ($row) {
                $updatePurchase = sectionPurchases::find($request->permission);
                $updatePurchase->sub_total = $request->sumTotal;
                $updatePurchase->tax_value = $request->sumTax;
                $updatePurchase->discount = $request->sumDiscount;
                $updatePurchase->total = $request->sumFinal;
                $updatePurchase->save();
                if ($updatePurchase) {
                    $updateCost = sectionCost::limit(1)->where(['section_id' => $updatePurchase->section_id, 'code' => $request->code])->first();
                    $updateCost->qty -= $oldQty;
                    $updateCost->qty += $request->quantity;
                    $updateCost->save();
                    if ($updateCost) {
                        $flag = true;
                    }
                }
            }
        }
        if ($flag) {
            return ['status' => true, 'data' => 'تم التعديل بنجاح'];
        }
    }
    public function updatePurchase(Request $request)
    {
        $flag = false;
        $data = json_decode($request->materialArray);
        if ($request->type == 'store') {
            $updatePurchase = storePurchases::find($request->permission);
            if ($updatePurchase) {
                foreach ($data as $row) {
                    $saveDeatils = storePurchasesDetails::create([
                        'order_id' => $request->permission,
                        'code' => $row->code,
                        'name' => $row->itemName,
                        'expire' => $row->Expire,
                        'unit' => $row->unitName,
                        'qty' => $row->quantity,
                        'price' => $row->priceUnit,
                        'sub_total' => $row->totalUnit,
                        'tax' => $row->taxPrice,
                        'discount' => $row->discountPrice,
                        'total' => $row->finalTotal,
                    ]);
                    if ($saveDeatils) {
                        $updateCost = storeCost::limit(1)->where(['store_id' => $request->stores, 'code' => $row->code])->first();
                        $realPrice = $row->priceUnit;
                        if ($row->unitName == $updateCost->unit) {
                            if ($updateCost->f_price == 0) {
                                $updateCost->f_price = $realPrice;
                            }
                            $updateCost->l_price = $realPrice;
                            $avaPrice = (($updateCost->qty * $updateCost->average) + ($realPrice * $row->quantity)) / ($updateCost->qty + $row->quantity);
                            $updateCost->average = $avaPrice;
                            $updateCost->qty += $row->quantity;
                        } else {
                            $unitQty = stock_unit::limit(1)->where(['name' => $updateCost->unit])->select(['size'])->first();
                            $qtyUnit = $row->quantity / $unitQty->size;
                            $priceUnit = $realPrice * $unitQty->size;
                            if ($updateCost->f_price == 0) {
                                $updateCost->f_price = $priceUnit;
                            }
                            $updateCost->l_price = $priceUnit;
                            $avaPrice = (($updateCost->qty * $updateCost->average) + ($priceUnit * $qtyUnit)) / ($updateCost->qty + $qtyUnit);
                            $updateCost->average = $avaPrice;
                            $updateCost->qty += $qtyUnit;
                        }
                        $updateCost->save();
                        $materialLog = [
                            'user' => Auth::user()->id,
                            'code' => $row->code,
                            'type' => 'مشتريات',
                            'store' => $request->stores,
                        ];
                        $this->materialLog($materialLog);
                    }
                }
                $updatePurchase->sub_total = $request->sumTotal;
                $updatePurchase->tax_value = $request->sumTax;
                $updatePurchase->discount = $request->sumDiscount;
                $updatePurchase->total = $request->sumFinal;
                $updatePurchase->save();
                $flag = true;
            }
        } elseif ($request->type == 'section') {
            $updatePurchase = sectionPurchases::find($request->permission);
            if ($updatePurchase) {
                foreach ($data as $row) {
                    $saveDeatils = sectionPurchasesDetails::create([
                        'order_id' => $request->permission,
                        'code' => $row->code,
                        'name' => $row->itemName,
                        'expire' => $row->Expire,
                        'unit' => $row->unitName,
                        'qty' => $row->quantity,
                        'price' => $row->priceUnit,
                        'sub_total' => $row->totalUnit,
                        'tax' => $row->taxPrice,
                        'discount' => $row->discountPrice,
                        'total' => $row->finalTotal,
                    ]);
                    if ($saveDeatils) {
                        $updateCost = sectionCost::limit(1)->where(['section_id' => $request->sections, 'code' => $row->code])->first();
                        $realPrice = $row->priceUnit;
                        if ($row->unitName == $updateCost->unit) {
                            if ($updateCost->f_price == 0) {
                                $updateCost->f_price = $realPrice;
                            }
                            $updateCost->l_price = $realPrice;
                            $avaPrice = (($updateCost->qty * $updateCost->average) + ($realPrice * $row->quantity)) / ($updateCost->qty + $row->quantity);
                            $updateCost->average = $avaPrice;
                            $updateCost->qty += $row->quantity;
                        } else {
                            $unitQty = stock_unit::limit(1)->where(['name' => $updateCost->unit])->select(['size'])->first();
                            $qtyUnit = $row->quantity / $unitQty->size;
                            $priceUnit = $realPrice * $unitQty->size;
                            if ($updateCost->f_price == 0) {
                                $updateCost->f_price = $priceUnit;
                            }
                            $updateCost->l_price = $priceUnit;
                            $avaPrice = (($updateCost->qty * $updateCost->average) + ($priceUnit * $qtyUnit)) / ($updateCost->qty + $qtyUnit);
                            $updateCost->average = $avaPrice;
                            $updateCost->qty += $qtyUnit;
                        }
                        $updateCost->save();
                        $materialLog = [
                            'user' => Auth::user()->id,
                            'code' => $row->code,
                            'type' => 'مشتريات',
                            'section' => $request->sections,
                        ];
                        $this->materialLog($materialLog);
                    }
                }
                $updatePurchase->sub_total = $request->sumTotal;
                $updatePurchase->tax_value = $request->sumTax;
                $updatePurchase->discount = $request->sumDiscount;
                $updatePurchase->total = $request->sumFinal;
                $updatePurchase->save();
                $flag = true;
            }
        }
        if ($flag) {
            return ['status' => true, 'data' => 'تم التعديل بنجاح'];
        }
    }
}
