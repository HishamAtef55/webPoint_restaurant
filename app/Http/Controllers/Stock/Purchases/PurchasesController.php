<?php

namespace App\Http\Controllers\Stock\Purchases;

use App\Enums\Unit;

use App\Models\Branch;
use App\Models\Purchases;
use Illuminate\View\View;
use App\Models\Stock\Store;
use Illuminate\Http\Request;
use App\Enums\PurchasesMethod;
use App\Models\Stock\Material;
use App\Models\Stock\Supplier;
use App\Models\PurchasesDetails;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Stock\MaterialResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Stock\PurchasesResource;
use App\Http\Requests\Stock\Material\StoreMaterialRequest;
use App\Http\Requests\Stock\Material\UpdateMaterialRequest;
use App\Http\Requests\Stock\Purchases\StorePurchasesRequest;
use App\Http\Requests\Stock\Purchases\UpdatePurchasesRequest;

class PurchasesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $lastPurchaseslNr = Purchases::latest()->first()?->id + 1 ?? 1;
        $invoices  = Purchases::get();
        $stores    = Store::get();
        $branches = Branch::get();
        $suppliers = Supplier::get();
        $materials = Material::with('details')->get();
        $materials->map(function ($material) {
            $material->details =   $material->details()->latest('created_at')->first();
            $material->unit = Unit::view($material->unit);
        });
        return view('stock.stock.purchases', compact('lastPurchaseslNr', 'stores', 'branches', 'suppliers', 'materials', 'invoices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePurchasesRequest  $request
     * @return PurchasesResource
     */
    public function store(
        StorePurchasesRequest  $request
    ): PurchasesResource {
        $data = [
            'serial_nr' => $request->validated()['serial_nr'],
            'purchases_method' => $request->validated()['purchases_method'],
            'supplier_id' => $request->validated()['supplier_id'],
            'user_id' => Auth::id(),
            'purchases_date' => $request->validated()['purchases_date'],
            'payment_type' => $request->validated()['payment_type'],
            'tax' => $request->validated()['tax'],
            'total' => $request->validated()['sumTotal'] * 100,
            'note' => $request->validated()['notes'],

        ];

        if ($request->validated()['purchases_image'] != 'undefined') {
            $data['image'] = $this->storeInvoiceImage($request->validated()['purchases_image']);
        }

        if ($request->validated()['purchases_method'] === PurchasesMethod::STORES->value) {
            $data['store_id'] = $request->validated()['store_id'];
            $data['section_id'] = null;
        } elseif ($request->validated()['purchases_method'] === PurchasesMethod::SECTIONS->value) {
            $data['section_id'] = $request->validated()['section_id'];
            $data['store_id'] = null;
        }

        $invoice = Purchases::create($data);
        if ($invoice) {
            $materials  =  json_decode($request->validated()['materialArray']);
            foreach ($materials  as $material) {
                $invoice->details()->create([
                    'material_id' => $material->material_id,
                    'expire_date' => $material->expire_date,
                    'qty' => $material->qty,
                    'price' => $material->price * 100,
                    'discount' => $material->discount * 100,
                    'total' => $material->total * 100,
                ]);
            }

            return PurchasesResource::make(
                $invoice
            )->additional([
                'message' => 'تم إنشاء الفاتورة بنجاح',
                'status' => Response::HTTP_CREATED
            ], Response::HTTP_CREATED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Purchases  $purchase
     * @return PurchasesResource
     */
    public function show(
        Purchases  $purchase
    ): PurchasesResource {
        return PurchasesResource::make(
            $purchase
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  UpdatePurchasesRequest  $request
     * @param  Purchases  $purchase
     * @return PurchasesResource
     */
    public function update(
        Purchases  $purchase,
        UpdatePurchasesRequest  $request
    ): PurchasesResource {
        $data = [
            'serial_nr' => $request->validated()['serial_nr'],
            'purchases_method' => $request->validated()['purchases_method'],
            'supplier_id' => $request->validated()['supplier_id'],
            'user_id' => Auth::id(),
            'purchases_date' => $request->validated()['purchases_date'],
            'payment_type' => $request->validated()['payment_type'],
            'tax' => $request->validated()['tax'],
            'total' => $request->validated()['sumTotal'] * 100,
            'note' => $request->validated()['notes'],

        ];

        if ($request->validated()['purchases_image'] != 'undefined') {
            $data['image'] = $this->storeInvoiceImage($request->validated()['purchases_image']);
        }

        if ($request->validated()['purchases_method'] === PurchasesMethod::STORES->value) {
            $data['store_id'] = $request->validated()['store_id'];
            $data['section_id'] = null;
        } elseif ($request->validated()['purchases_method'] === PurchasesMethod::SECTIONS->value) {
            $data['section_id'] = $request->validated()['section_id'];
            $data['store_id'] = null;
        }

        $purchase->update($data);
        $purchase->details()->delete();
        $materials  =  json_decode($request->validated()['materialArray']);
        foreach ($materials  as $material) {
            $purchase->details()->create([
                'material_id' => $material->material_id,
                'expire_date' => $material->expire_date,
                'qty' => $material->qty,
                'price' => $material->price * 100,
                'discount' => $material->discount * 100,
                'total' => $material->total * 100,
            ]);
        }



        return PurchasesResource::make(
            $purchase
        )->additional([
            'message' => "تم تعديل الفاتورة بنجاح",
            'status' => Response::HTTP_OK
        ]);
    }

    /**
     * destroy
     *
     * @param  Purchases  $purchase
     * @return PurchasesResource
     */
    public function destroy(
        Purchases  $purchase,
        Request $request
    ): PurchasesResource {
        if ($purchase->details()->where('id', $request->details_id)->delete()) {

            return PurchasesResource::make(
                $purchase
            )->additional([
                'message' => "تم حذف الخامة",
                'status' => Response::HTTP_OK
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  $image
     * @return string
     */
    private function storeInvoiceImage($image): string
    {
        $extension = $image->getClientOriginalExtension();
        $fileName = time() . '_' . rand(1000, 9999) . '.' . $extension;
        $path = 'stock/images/purchases';
        $image->move(public_path($path), $fileName);
        return asset($path . '/' . $fileName);
    }
}
