<?php

namespace App\Http\Controllers\Stock\Purchases;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\Stock\Material;
use App\Http\Controllers\Controller;
use App\Http\Resources\Stock\MaterialResource;
use App\Http\Resources\Stock\SectionResource;
use App\Models\Stock\Section;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FilterSections extends Controller
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

        return SectionResource::collection(
            Section::whereBelongsTo($branch)
                ->get()
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }
}
