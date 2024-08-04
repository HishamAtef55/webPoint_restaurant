<?php

namespace App\Http\Resources\Stock;

use Money\Money;
use App\Enums\Unit;
use App\Casts\StorageCast;
use App\Enums\StorageType;
use App\Enums\MaterialType;
use App\Foundation\Moneys\Moneys;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
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
            'name' => $this->name,
            'cost' => $this->cost,
            'price' => (new Moneys($this->price))->format(),
            'unit' =>  Unit::view($this->unit),
            'loss_ratio' => $this->loss_ratio,
            'serial_nr' => $this->serial_nr,
            'min_store' => $this->min_store,
            'max_store' => $this->max_store,
            'min_section' => $this->min_section,
            'max_section' => $this->max_section,
            'storage_type' => StorageType::view($this->storage_type),
            'material_type' => MaterialType::view($this->material_type),
            'expire_date' => $this->expire_date,
            'group' => StockGroupResource::make($this->group),
            'branch' => BranchResource::make($this->branch),
            'sections' => SectionResource::collection($this->sections)
        ];
    }
}
