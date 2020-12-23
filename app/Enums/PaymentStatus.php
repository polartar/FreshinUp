<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PaymentStatus extends Enum
{
    const PENDING = 1; // awaiting payment, before due date
    const OVERDUE = 2; // awaiting payment, after due date
    const PAID = 3; // payment was completed
    const FAILED = 4; // an error occurred
    const REFUNDED = 5; // FF staff refunded partial or full amount

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
