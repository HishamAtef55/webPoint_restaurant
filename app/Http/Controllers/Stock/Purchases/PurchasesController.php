<?php

namespace App\Http\Controllers\Stock\Purchases;

use App\Enums\Unit;

use App\Models\Branch;
use Illuminate\View\View;
use App\Models\Stock\Store;
use Illuminate\Http\Request;
use App\Enums\PurchasesMethod;
use App\Models\Stock\Material;
use App\Models\Stock\Supplier;
use App\Models\Stock\Purchases;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Stock\PurchasesDetails;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Stock\MaterialResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Stock\PurchasesResource;
use App\Http\Requests\Stock\Material\StoreMaterialRequest;
use App\Http\Requests\Stock\Material\UpdateMaterialRequest;
use App\Http\Requests\Stock\Purchases\StorePurchasesRequest;
use App\Http\Requests\Stock\Purchases\UpdatePurchasesRequest;
use App\Invoices\Invoice;
use App\Models\Stock\StoreBalance;

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
        return view('stock.Purchases.index', compact('lastPurchaseslNr', 'stores', 'branches', 'suppliers', 'materials', 'invoices'));
    }

    /**
     * store
     *
     * @param  StorePurchasesRequest  $request
     * @param  Invoice $invoice
     * @return JsonResponse
     */
    public function store(
        StorePurchasesRequest  $request,
        Invoice $invoice
    ): JsonResponse {
        if ($invoice->create($request->validated())) {
            return response()->json([
                'message' => 'تم إنشاء الفاتورة بنجاح',
                'status' => Response::HTTP_CREATED
            ], Response::HTTP_CREATED);
        }
        return response()->json([
            'message' => 'حدث خطأ',
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * show
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
     * update
     *
     * @param  UpdatePurchasesRequest  $request
     * @param  Purchases  $purchase
     * @return PurchasesResource
     */
    public function update(
        Purchases  $purchase,
        UpdatePurchasesRequest  $request,
        Invoice $invoice
    ): PurchasesResource {
        if ($invoice->update($request->validated(), $purchase)) {
            return PurchasesResource::make(
                $purchase
            )->additional([
                'message' => 'تم تعديل الفاتورة بنجاح',
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        }
    }

    /**
     * destroy
     *
     * @param  Purchases  $purchase
     * @return PurchasesResource|JsonResponse
     */
    public function destroy(
        Purchases  $purchase,
        Request $request,
        Invoice $invoice
    ): PurchasesResource|JsonResponse {
        if ($invoice->delete($purchase, $request->details_id)) {
            return response()->json([
                'message' => "تم حذف الخامة",
                'status' => Response::HTTP_OK
            ]);
        } else {
            return response()->json([
                'message' => 'لا يمكن حذف خامة على الاقل',
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
