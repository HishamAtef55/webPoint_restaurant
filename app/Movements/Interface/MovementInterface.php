<?php

namespace App\Movements\Interface;

use App\Models\Stock\Store;
use App\Models\Stock\Section;
use  Illuminate\Database\Eloquent\Collection;

interface MovementInterface
{

    /**
     * create
     * @param Section|Store $model
     * @return bool
     */
    public function create(
        Section|Store $model,
    ): bool;
}
