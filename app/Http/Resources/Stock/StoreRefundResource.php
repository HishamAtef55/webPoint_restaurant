<?php

namespace App\Http\Resources\Stock;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreRefundResource extends JsonResource
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
            'serial_nr' => $this->serial_nr,
            'section' => SectionResource::make($this->section),
            'store' => StoreResource::make($this->store),
            'refund_date' => $this->refund_date,
            'notes' => $this->notes,
            'total' => $this->total / 100,
            'details' => MaterialMovementDetailsResource::collection($this->details),
        ];
    }
}
