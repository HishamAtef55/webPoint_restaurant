<?php

namespace App\Http\Controllers\Stock\MaterialHalk\Item;

use App\Models\Item;
use App\Models\Branch;
use App\Models\DetailsItem;
use App\Models\Stock\Section;
use App\Http\Controllers\Controller;
use App\Http\Resources\Stock\ItemResource;
use App\Http\Resources\Stock\SectionResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Stock\ItemsDetailsResource;
use Symfony\Component\HttpFoundation\JsonResponse;



class FilterSectionItemsController extends Controller
{
    /**
     * __invoke
     *
     * @param Branch $branch
     * @return JsonResponse
     */
    public function __invoke(
        Branch $branch
    ): JsonResponse {

        try {

            $sections = Section::has('balance')->whereBelongsTo($branch)->get();
            $items    = Item::has('material_components')->whereBelongsTo($branch)->get();
            return response()->json([
                'sections' => SectionResource::collection($sections),
                'items'    => ItemResource::collection($items),
                'messgae'  => null,
                'status'   => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Throwable $e) {

            return response()->json([
                'messgae'  => $e->getMessage(),
                'status'   => $e->getCode()
            ], $e->getCode());
        }
    }
}
