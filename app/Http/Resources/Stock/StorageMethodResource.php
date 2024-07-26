<?php

namespace App\Http\Resources\Stock;

use App\Enums\Unit;
use App\Enums\StorageType;
use Illuminate\Http\Resources\Json\JsonResource;

class StorageMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'store_id' => $this->store_id,
            'type' => StorageType::view($this->type),
            'unit' =>  Unit::view($this->unit),
            'capacity' => $this->capacity,
        ];
    }
}
