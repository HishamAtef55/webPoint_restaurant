<?php

namespace App\Casts;

use App\Enums\MaterialType;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class MaterialCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): MaterialType
    {
        $material = MaterialType::tryFrom($value);
        if (!$material) {
            throw new InvalidArgumentException("Invalid material value: $value");
        }
        return $material;
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        if (is_string($value)) {
            $value = MaterialType::tryFrom($value);
        }
        if (!$value instanceof MaterialType) {
            throw new InvalidArgumentException('The given value is not an instance of material enum.');
        }
        return $value->value;
    }
}
