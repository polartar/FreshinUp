<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class EventType extends Enum
{
    const CATERING = 1;
    const CASH_AND_CARRY = 2;

    public static function toKeyedSelectArray()
    {
        return json_encode(json_encode(static::toSelectArray()));
    }

    public static function toKeyedArray()
    {

        $array = self::toArray();
        $selectArray = [];

        foreach ($array as $key => $value) {
            array_push($selectArray, [
                'value' => $value,
                'key' => $key,
                'label' => static::getDescription($value)
            ]);
        }
    }
}
