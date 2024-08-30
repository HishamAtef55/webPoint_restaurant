<?php

namespace App\Http\Controllers\Stock\Exchange;

use App\Models\Branch;
use App\Models\Stock\Store;
use Illuminate\Http\Request;
use App\Models\Stock\Exchange;
use App\Services\ExchangeService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Stock\ExchangeResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Stock\PurchasesResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Stock\Exchange\StoreExchangeRequest;
use App\Http\Requests\Stock\Exchange\UpdateExchangeRequest;

class ExchangeController extends Controller
{

    /**
     * index
     *
     * @return view
     */
    public function index()
    {
        $lastExchangeNr = Exchange::latest()->first()?->id + 1 ?? 1;
        $orders = Exchange::get();
        $stores = Store::get();
        $branches = Branch::get();
        return view('stock.Exchange.index', compact('lastExchangeNr', 'stores', 'branches', 'orders'));
    }

    /**
     * store
     *
     * @param  StorePurchasesRequest  $request
     * @param ExchangeService $exchange
     * @return JsonResponse
     */
    public function store(
        StoreExchangeRequest  $request,
        ExchangeService $exchange,
    ): JsonResponse {
        if ($exchange->store($request->validated())) {
            return response()->json([
                'message' => 'تم إنشاء إذن الصرف بنجاح',
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
     * @param  Exchange  $exchange
     * @return ExchangeResource
     */
    public function show(
        Exchange  $exchange
    ): ExchangeResource {
        return ExchangeResource::make(
            $exchange
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }

    /**
     * update
     *
     * @param  Exchange  $exchange
     * @param  UpdateExchangeRequest  $request
     * @param  ExchangeService $service,
     * @return ExchangeResource
     */
    public function update(
        Exchange  $exchange,
        UpdateExchangeRequest  $request,
        ExchangeService $service,
    ): ExchangeResource {

        if ($service->update($request->validated(), $exchange)) {
            return ExchangeResource::make(
                $exchange
            )->additional([
                'message' => 'تم تعديل إذن الصرف بنجاح بنجاح',
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        }
    }

    /**
     * destroy
     *
     * @param  Exchange  $exchange
     * @param  Request $reques
     * @param  ExchangeService $service
     * @return ExchangeResource|JsonResponse
     */
    public function destroy(
        Exchange  $exchange,
        Request $request,
        ExchangeService $service
    ): ExchangeResource|JsonResponse 
    {
        if ($service->delete($exchange, $request->details_id)) {
            return response()->json([
                'message' => "تم حذف الخامة",
                'status' => Response::HTTP_OK
            ]);
        }

        return response()->json([
            'message' => 'لا يمكن حذف خامة على الاقل',
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
