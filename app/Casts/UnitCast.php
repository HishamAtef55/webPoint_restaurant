<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use App\Enums\Unit;
use InvalidArgumentException;

class UnitCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): Unit
    {
        $unit = Unit::tryFrom($value);
        if (!$unit) {
            throw new InvalidArgumentException("Invalid unit value: $value");
        }
        return $unit;
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        if (is_string($value)) {
            $value = Unit::tryFrom($value);
        }
        if (!$value instanceof Unit) {
            throw new InvalidArgumentException('The given value is not an instance of Unit enum.');
        }
        return $value->value;
    }
}
