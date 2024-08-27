<?php

namespace App\Http\Controllers\Stock\Purchases;

use App\Enums\PurchasesMethod;
use App\Models\Stock\Material;
use App\Balances\Facades\Balance;
use App\Http\Controllers\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class FilterBalance extends Controller
{
    /**
     * __invoke
     *
     * @param Material $material
     * @return JsonResponse
     */
    public function __invoke(
        Material $material
    ): JsonResponse {
        $balance = match ($_REQUEST['type']) {
            PurchasesMethod::STORES->value => Balance::storeBalance()->currentBalanceByMaterial($material, $_REQUEST['store_id']),
            PurchasesMethod::SECTIONS->value => Balance::sectionBalance()->currentBalanceByMaterial($material, $_REQUEST['section_id']),
            default => 0
        };
        return response()->json([
            'qty' => $balance,
            'message' => null,
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }
}
