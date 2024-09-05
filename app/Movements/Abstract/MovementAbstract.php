<?php

namespace App\Movements\Abstract;



abstract class MovementAbstract
{
    /*
     * The attributes that hold material move.
     *
     * @var array<int, string>
    */
    protected $movement = [];


    /*
     * The attributes that type of movement
     *
     * @var array<int, string>
    */
    protected $type = '';

    /**
     * Reset all movement-related properties
     */
    protected function resetMovement(): void
    {
        $this->movement = [];
        $this->type = '';
    }

    /**
     * validate
     * @param string $type
     * @param array $data
     * @return self
     */
    public function validate(
        string $type,
        array $data
    ): self {

        // Reset all previous movement data and type
        $this->resetMovement();

        // Set new movement data
        $this->movement = $data;

        // Set movement type
        $this->type = $type;

        return $this;
    }
}
