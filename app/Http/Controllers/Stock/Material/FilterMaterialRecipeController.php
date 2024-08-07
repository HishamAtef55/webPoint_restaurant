<?php

namespace App\Http\Controllers\Stock\Material;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\Stock\Material;
use App\Foundation\Moneys\Moneys;
use App\Http\Controllers\Controller;
use App\Models\Stock\MaterialRecipe;
use App\Http\Resources\Stock\MaterialResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Stock\MaterialRecipeResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FilterMaterialRecipeController extends Controller
{
    /**
     * __invoke
     *
     * @param Material $material
     * @return AnonymousResourceCollection
     */
    public function __invoke(
        Material $material
    ): AnonymousResourceCollection {

        return MaterialRecipeResource::collection(
            $material->recipes
        )->additional([
            'total_price' => $material->total_price / 100,
            'component_qty' => $material->component->qty,
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }
}
