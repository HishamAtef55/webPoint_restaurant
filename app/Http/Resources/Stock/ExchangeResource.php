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
            'order_nr' => $this->order_nr,
            'exchange_date' => $this->exchange_date,
            'section' => SectionResource::make($this->section),
            'store' => StoreResource::make($this->store),
            'note' => $this->notes,
            'details' => ExchangeDetailsResource::collection($this->details),
        ];
    }
}
