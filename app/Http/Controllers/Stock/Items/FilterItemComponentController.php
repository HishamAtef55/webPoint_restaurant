<?php

namespace App\Http\Controllers\Stock\Items;

use App\Models\Item;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Stock\ItemResource;
use App\Http\Resources\Stock\MaterialResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FilterItemComponentController extends Controller
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
            Item::whereNotIn(
                'id',[$branch->mainComponent->pluck('item')]
            )->get()
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }
}
