<?php

namespace App\Http\Resources\Stock;

use App\Enums\Unit;
use Illuminate\Http\Resources\Json\JsonResource;

class BalanceResource extends JsonResource
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
            'material' => [
                'id' => $this->material->id,
                'name' => $this->material->name,
                'unit' => Unit::view($this->material->unit),
            ],
            'qty' => $this->qty,
            'avg_price' => $this->avg_price / 100
        ];
    }
}
