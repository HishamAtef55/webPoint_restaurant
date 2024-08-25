<?php

namespace App\Movements\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @author Hisham Atef
 *
 * @see \App\Movements\SectionMaterialMovement;
 * @see \App\Movements\StoreMaterialMovement;
 */
class Movement extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     */
    public static function getFacadeAccessor(): string
    {
        return 'movements';
    }
}
