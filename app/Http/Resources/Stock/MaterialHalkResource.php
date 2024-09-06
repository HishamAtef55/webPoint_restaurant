<?php

namespace App\Http\Resources\Stock;

use App\Enums\PurchasesMethod;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\stock\MaterialHalkDetailsResource;

class MaterialHalkResource extends JsonResource
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
            'halk_date' => $this->halk_date,
            'halk_type' => $this->halk_type,
            'image' => $this->image,
            'notes' => $this->notes,
            'details' => MaterialHalkDetailsResource::collection($this->details),
            $this->mergeWhen($this->halk_type == PurchasesMethod::SECTIONS->value, [
                'section' => SectionResource::make($this->section),
            ]),
            $this->mergeWhen($this->halk_type == PurchasesMethod::STORES->value, [
                'store' => StoreResource::make($this->store),
            ]),
        ];
    }
}
