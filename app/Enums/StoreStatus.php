<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class StoreStatus extends Enum
{
    const DRAFT = 1;
    const PENDING = 2;
    const REVISION = 3;
    const REJECTED = 4;
    const APPROVED = 5;
    const ON_HOLD = 6;

    public static function toKeyedSelectArray()
    {
        return json_decode(json_encode(static::toSelectArray()));
    }

    public static function toKeyedArray()
    {
        $array = self::toArray();
        $selectArray = [];

        foreach ($array as $key => $value) {
            array_push($selectArray, [
                'value' => $value,
                'key' => $key,
                'label' => static::getDescription($value),
            ]);
        }

        return $selectArray;
    }
}
