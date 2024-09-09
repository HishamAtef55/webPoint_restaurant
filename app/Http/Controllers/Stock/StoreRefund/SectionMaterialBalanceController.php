<?php

namespace App\Http\Controllers\Stock\StoreRefund;

use App\Models\Stock\Store;
use App\Http\Controllers\Controller;
use App\Http\Resources\Stock\SectionResource;
use App\Http\Resources\Stock\StoreResource;
use App\Models\Stock\Section;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SectionMaterialBalanceController extends Controller
{
    /**
     * __invoke
     *
     * @param Section $section
     * @return StoreResource
     */
    public function __invoke(
        Section $section
    ): SectionResource {
        return SectionResource::make(
            $section->load('balance', 'storageCapacity')
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }
}
