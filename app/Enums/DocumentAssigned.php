<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\Event;
use App\User;

final class DocumentAssigned extends Enum
{
    const USER = 1;
    const STORE = 2;
    const VENUE = 3;
    const EVENT = 4;

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

    public static function getKeyUseDescription($description)
    {
        $array = static::toSelectArray($description);

        foreach ($array as $key => $value) {
            if ($description === $value) {
                return $key;
            }
        }

        return self::USER;
    }

    public static function getDescription($value): string
    {
        if ($value === self::USER) {
            return 'App\User';
        }

        if ($value === self::STORE) {
            return 'App\Models\Foodfleet\Store';
        }

        if ($value === self::VENUE) {
            return 'App\Models\Foodfleet\Location';
        }

        if ($value === self::EVENT) {
            return 'App\Models\Foodfleet\Event';
        }

        return parent::getDescription($value);
    }

    public static function getResource($value): string
    {
        if ($value === self::USER) {
            return 'FreshinUp\FreshBusForms\Http\Resources\User\User';
        }

        if ($value === self::STORE) {
            return 'App\Http\Resources\Foodfleet\Store';
        }

        if ($value === self::VENUE) {
            return 'App\Http\Resources\Foodfleet\Location';
        }

        if ($value === self::EVENT) {
            return 'App\Http\Resources\Foodfleet\Event';
        }

        return '';
    }
}
