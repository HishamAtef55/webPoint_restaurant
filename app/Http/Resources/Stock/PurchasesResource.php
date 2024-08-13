<?php

namespace App\Http\Resources\Stock;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchasesResource extends JsonResource
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
            'purchases_method' => $this->purchases_method,
            'supplier' => SupplierResource::make($this->supplier),
            'section' => SectionResource::make($this->section),
            'store' => StoreResource::make($this->store),
            'purchases_date' => $this->purchases_date,
            'payment_type' => $this->payment_type,
            'tax' => $this->tax,
            'note' => $this->note,
            'total' => $this->total / 100,
            'details' => PurchasesDetailsResource::collection($this->details),
        ];
    }
}
