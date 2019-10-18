<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class EventStatus extends Enum
{
    const DRAFT = 1;
    const PENDING = 2;
    const CONFIRMED = 3;
    const PAST = 4;
    const CANCELLED = 5;

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
