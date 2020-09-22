<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class VenueStatus extends Enum
{
    const PENDING = 1;
    const APPROVED = 2;
    const REJECTED = 3;
    const EXPIRING = 4;
    const EXPIRED = 5;

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
