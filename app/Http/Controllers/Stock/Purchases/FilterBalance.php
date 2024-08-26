<?php

namespace App\Http\Controllers\Stock\Purchases;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\Stock\Section;
use App\Enums\PurchasesMethod;
use App\Models\Stock\Material;
use App\Balances\Facades\Balance;
use App\Http\Controllers\Controller;
use App\Http\Resources\Stock\SectionResource;
use App\Http\Resources\Stock\MaterialResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FilterBalance extends Controller
{
    /**
     * __invoke
     *
     * @param Material $material
     * @return AnonymousResourceCollection
     */
    public function __invoke(
        Material $material
    ) {
        $balance = match ($_REQUEST['type']) {
            PurchasesMethod::STORES->value => Balance::storeBalance()->currentBalance($material, $_REQUEST['store_id']),
            PurchasesMethod::SECTIONS->value => Balance::sectionBalance()->currentBalance($material, $_REQUEST['section_id']),
            default => 0
        };
        return response()->json([
            'qty' => $balance,
            'message' => null,
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }
}
