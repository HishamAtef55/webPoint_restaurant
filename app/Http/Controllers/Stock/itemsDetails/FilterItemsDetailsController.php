<?php

namespace App\Http\Controllers\Stock\ItemsDetails;

use App\Models\Branch;
use App\Models\Details;
use App\Models\DetailsItem;
use Illuminate\Http\Request;
use Illuminate\View\Component;
use App\Models\detailsComponent;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Stock\ItemsDetailsResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FilterItemsDetailsController extends Controller
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
        return ItemsDetailsResource::collection(
            DetailsItem::doesntHave('details_material_components')
                ->whereBelongsTo($branch)
                ->get()
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }
}
