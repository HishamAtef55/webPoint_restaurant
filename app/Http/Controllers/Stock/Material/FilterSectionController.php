<?php

namespace App\Http\Controllers\Stock\Material;

use App\Http\Resources\Stock\SectionResource;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Branch;
use App\Models\Stock\Section;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FilterSectionController
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
            $branch->sections
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }
}
