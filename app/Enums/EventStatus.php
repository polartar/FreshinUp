<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class EventStatus extends Enum
{
    const DRAFT = 1;
    const FF_INITIAL_REVIEW = 2;
    const CUSTOMER_AGREEMENT = 3;
    const FLEET_MEMBER_SELECTION = 4;
    const CUSTOMER_REVIEW = 5;
    const FLEET_MEMBER_CONTRACTS = 6;
    const CONFIRMED = 7;
    const CANCELLED = 8;
    const PAST = 9;

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
