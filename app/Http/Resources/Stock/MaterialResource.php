<?php

namespace App\Http\Resources\Stock;

use App\Enums\Unit;
use App\Casts\StorageCast;
use App\Enums\StorageType;
use App\Enums\MaterialType;
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
            'price' => $this->price,
            'unit' =>  $this->unitTranslate($this->unit),
            'loss_ratio' => $this->loss_ratio,
            'serial_nr' => $this->serial_nr,
            'min_store' => $this->min_store,
            'max_store' => $this->max_store,
            'min_section' => $this->min_section,
            'max_section' => $this->max_section,
            'storage_type' => $this->storageTranslate($this->storage_type),
            'material_type' => $this->materialTranslate($this->material_type),
            'expire_date' => $this->expire_date,
            'group' => StockGroupResource::make($this->group),
            'branch' => BranchResource::make($this->branch),
            'sections' => SectionResource::collection($this->sections)
        ];
    }
}
