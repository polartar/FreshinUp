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
    const EVENT_STORE = 5;

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

    public static function getKeyUseDescription($description, $isEventStore)
    {
        $array = static::toSelectArray($description);

        if ($isEventStore) {
            return self::EVENT_STORE;
        }

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
            return 'App\Models\Foodfleet\Venue';
        }

        if ($value === self::EVENT) {
            return 'App\Models\Foodfleet\Event';
        }

        if ($value === self::EVENT_STORE) {
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
            return 'App\Http\Resources\Foodfleet\Store\Store';
        }

        if ($value === self::VENUE) {
            return 'App\Http\Resources\Foodfleet\Venue';
        }

        if ($value === self::EVENT) {
            return 'App\Http\Resources\Foodfleet\Event';
        }

        if ($value === self::EVENT_STORE) {
            return 'App\Http\Resources\Foodfleet\Event';
        }

        return '';
    }
}
