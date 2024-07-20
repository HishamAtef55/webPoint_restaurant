<?php

namespace App\Http\Controllers\Stock\Material;

use Illuminate\Http\Request;
use App\Models\Stock\StockGroup;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Stock\StockGroupResource;
use App\Http\Requests\Stock\SubGroups\FilterSubGroupRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FilterSubGroupController extends Controller
{
    /**
     * __invoke
     *
     * @param FilterSubGroupRequest $request
     * @return AnonymousResourceCollection
     */
    public function __invoke(
        FilterSubGroupRequest $request
    ): AnonymousResourceCollection {

        return StockGroupResource::collection(
            StockGroup::treeOf(function (Builder $builder) use ($request) {
                return $builder->where('parent_id', $request->validated('parent_id'));
            })->get()
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }
}
