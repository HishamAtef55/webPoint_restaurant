<?php

namespace App\Http\Resources\Stock;

use App\Enums\PurchasesMethod;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialTransferResource extends JsonResource
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
            'transfer_date' => $this->transfer_date,
            'transfer_type' => $this->transfer_type,
            'image' => $this->image,
            'notes' => $this->notes,
            'details' => MaterialTransferDetailsResource::collection($this->details),
            $this->mergeWhen($this->transfer_type == PurchasesMethod::SECTIONS->value, [
                'from_section' => SectionResource::make($this->from_section),
                'to_section' => SectionResource::make($this->to_section),
            ]),
            $this->mergeWhen($this->transfer_type == PurchasesMethod::STORES->value, [
                'from_store' => StoreResource::make($this->from_store),
                'to_store' => StoreResource::make($this->to_store),
            ]),
        ];
    }
}
