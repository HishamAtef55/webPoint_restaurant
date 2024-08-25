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
     * validate
     * @param string $type
     * @param array $data
     * @return self
     */
    public function validate(
        string $type,
        array $data
    ): self {
        $this->movement  = $data;
        $this->type = $type;
        return $this;
    }
}
