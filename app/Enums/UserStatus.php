<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserStatus extends Enum
{
    const LEAD = 1;
    const PENDING_INVITATION = 2;
    const PENDING_REVIEW = 3;
    const APPROVED = 4;
    const ON_HOLD = 5;

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
