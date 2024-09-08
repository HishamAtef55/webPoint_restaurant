<?php

namespace App\Http\Resources\Stock;

use Illuminate\Http\Resources\Json\JsonResource;

class MaterialHalkItemResource extends JsonResource
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
            'halk_item_date' => $this->halk_item_date,
            'notes' => $this->notes,
            'details' => MaterialHalkItemDetailsResource::collection($this->details),
            'section' => SectionResource::make($this->section),
        ];
    }
}
