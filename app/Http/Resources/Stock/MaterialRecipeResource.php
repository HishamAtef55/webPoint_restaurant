<?php

namespace App\Http\Resources\Stock;

use Money\Money;
use App\Foundation\Moneys\Moneys;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialRecipeResource extends JsonResource
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
            'material_id' => $this->material_id,
            'material_recipe_id' => $this->material_recipe_id,
            'material_recipe_name' => $this->material->name,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'price' => $this->price / 100,
            'display_price' => (new Moneys($this->price))->format(),

        ];
    }
}
