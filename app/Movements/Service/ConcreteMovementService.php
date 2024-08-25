<?php

namespace App\Movements\Service;


use App\Movements\StoreMaterialMovement;
use App\Movements\SectionMaterialMovement;

class ConcreteMovementService
{
    protected $storeMaterialMove;
    protected $sectionMaterialMove;

    public function __construct()
    {
        $this->storeMaterialMove = new StoreMaterialMovement();
        $this->sectionMaterialMove = new SectionMaterialMovement();
    }

    public function storeMaterialMovement()
    {
        return $this->storeMaterialMove;
    }

    public function sectionMaterialMovement()
    {
        return $this->sectionMaterialMove;
    }
}
