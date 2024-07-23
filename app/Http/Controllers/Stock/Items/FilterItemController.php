<?php

namespace App\Http\Controllers\Stock\Items;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Stock\ItemResource;
use App\Http\Resources\Stock\MaterialResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FilterItemController extends Controller
{
    /**
     * __invoke
     *
     * @param Branch $branch
     * @return AnonymousResourceCollection
     */
    public function __invoke(
        Branch $branch
    ): AnonymousResourceCollection {

        return ItemResource::collection(
            $branch->items
        )->additional([
            'materials' => MaterialResource::collection($branch->materials),
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }
}
