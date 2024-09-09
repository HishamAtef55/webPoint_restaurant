<?php

namespace App\Http\Resources\Stock;

use Illuminate\Http\Resources\Json\JsonResource;

class ExchangeResource extends JsonResource
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
            'exchange_nr' => $this->exchange_nr,
            'exchange_date' => $this->exchange_date,
            'section' => SectionResource::make($this->section),
            'store' => StoreResource::make($this->store),
            'note' => $this->notes,
            'total' => $this->total / 100,
            'details' => MaterialMovementDetailsResource::collection($this->details),
        ];
    }
}
