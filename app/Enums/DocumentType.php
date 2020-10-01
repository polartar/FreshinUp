<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class DocumentType extends Enum
{
    const FROM_TEMPLATE = 1;
    const DOWNLOADABLE = 2;

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
