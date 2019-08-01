<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserStatus extends Enum
{
    const ACTIVE = 1;
    const INACTIVE = 2;
    const HOLD = 3;
    const PENDING = 4;
    const PROSPECT = 5;
    const ISNEW = 6;

    public static function isInactive($value)
    {
        return $value === self::INACTIVE;
    }

    public static function isPending($value)
    {
        return $value === self::PENDING;
    }

    public static function isActive($value)
    {
        return $value === self::ACTIVE;
    }

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
