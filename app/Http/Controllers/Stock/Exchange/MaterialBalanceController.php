<?php

namespace App\Http\Controllers\Stock\Exchange;

use App\Models\Stock\Store;
use App\Http\Controllers\Controller;
use App\Http\Resources\Stock\StoreResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MaterialBalanceController extends Controller
{
    /**
     * __invoke
     *
     * @param Store $store
     * @return StoreResource
     */
    public function __invoke(
        Store $store
    ): StoreResource {
        return StoreResource::make(
            $store->load('balance', 'storageCapacity')
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }
}
