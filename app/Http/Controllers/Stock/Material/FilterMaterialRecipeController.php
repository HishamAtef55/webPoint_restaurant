<?php

namespace App\Http\Controllers\Stock\Material;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\Stock\Material;
use App\Http\Controllers\Controller;
use App\Http\Resources\Stock\MaterialResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FilterMaterialRecipeController extends Controller
{
    // $materials = Material::IsManufactured()->whereBelongsTo($branch)->get();
    /**
     * __invoke
     *
     * @param Branch $branch
     * @return AnonymousResourceCollection
     */
    public function __invoke(
        Branch $branch
    ): AnonymousResourceCollection {

        return MaterialResource::collection(
            Material::isManufactured()
                ->whereBelongsTo($branch)
                ->get()
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }
}
