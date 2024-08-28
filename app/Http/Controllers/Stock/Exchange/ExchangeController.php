<?php

namespace App\Http\Controllers\Stock\Exchange;

use App\Models\Branch;
use App\Models\Stock\Store;
use App\Models\Stock\Exchange;
use App\Services\ExchangeService;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Stock\Exchange\StoreExchangeRequest;

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
}
