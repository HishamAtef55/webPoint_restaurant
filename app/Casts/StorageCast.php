<?php

namespace App\Casts;

use App\Enums\MaterialType;
use App\Enums\StorageType;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class StorageCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): StorageType
    {
        $storage = StorageType::tryFrom($value);
        if (!$storage) {
            throw new InvalidArgumentException("Invalid storage value: $value");
        }
        return $storage;
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        if (!$value instanceof StorageType) {
            throw new InvalidArgumentException('The given value is not an instance of storage enum.');
        }
        return $value->value;
    }
}
