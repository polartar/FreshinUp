<?php

namespace App\Enums;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\Venue;
use App\User;
use BenSampo\Enum\Enum;

final class DocumentAssigned extends Enum
{
    const USER = 1;
    const STORE = 2;
    const VENUE = 3;
    const EVENT = 4;
    const EVENT_STORE = 5;
    const LOCATION = 6;

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
            return User::class;
        }

        if ($value === self::STORE) {
            return Store::class;
        }

        if ($value === self::VENUE) {
            return Venue::class;
        }

        if ($value === self::EVENT || $value === self::EVENT_STORE) {
            return Event::class;
        }

        if ($value === self::LOCATION) {
            return Location::class;
        }

        return parent::getDescription($value);
    }

    public static function getResource($value): string
    {
        if ($value === self::USER) {
            return \FreshinUp\FreshBusForms\Http\Resources\User\User::class;
        }

        if ($value === self::STORE) {
            return \App\Http\Resources\Foodfleet\Store\Store::class;
        }

        if ($value === self::VENUE) {
            return \App\Http\Resources\Foodfleet\Venue::class;
        }

        if ($value === self::EVENT || $value === self::EVENT_STORE) {
            return \App\Http\Resources\Foodfleet\Event::class;
        }

        if ($value === self::LOCATION) {
            return \App\Http\Resources\Foodfleet\Location::class;
        }

        return '';
    }
}
