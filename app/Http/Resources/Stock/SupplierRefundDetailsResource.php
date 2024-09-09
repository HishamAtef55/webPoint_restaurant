<?php

namespace App\Http\Resources\Stock;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierRefundDetailsResource extends JsonResource
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
            'refund_id' => $this->stockable_id,
            'material_id' => $this->material_id,
            'expire_date' => $this->expire_date,
            'qty' => $this->qty,
            'price' => $this->price / 100,
            'total' => $this->total / 100,
            'material' => MaterialResource::make($this->materials)
        ];
    }
}
