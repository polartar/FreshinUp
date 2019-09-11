<?php

namespace App\Helpers;

use App\Filters\Payment\BelongsToWhereUuidEquals;
use App\Filters\Payment\CategoryUuid;
use FreshinUp\FreshBusForms\Filters\GreaterThanOrEqualTo;
use FreshinUp\FreshBusForms\Filters\LessThanOrEqualTo;
use Spatie\QueryBuilder\Filter;

class PaymentQueryBuilderHelper
{
    /**
     * Return an array of allowed filters for Transactions
     *
     * @param $prefix String
     * @return array
     */
    public static function getPaymentFilters()
    {
        return [
            Filter::exact('event_uuid'),
            Filter::exact('fleet_member_uuid', 'event.fleet_member_uuid'),
            Filter::exact('contractor_uuid', 'event.fleetMember.contractor_uuid'),
            Filter::custom('date_after', GreaterThanOrEqualTo::class, 'square_created_at'),
            Filter::custom('date_before', LessThanOrEqualTo::class, 'square_created_at'),
            Filter::custom('event_tag_uuid', BelongsToWhereUuidEquals::class, 'event.eventTags'),
            Filter::exact('location_uuid'),
            Filter::exact('customer_uuid'),
            Filter::custom('staff_uuid', BelongsToWhereUuidEquals::class, 'location.staffs'),
            Filter::exact('device_uuid'),
            Filter::custom('category_uuid', CategoryUuid::class),
            Filter::custom('item_uuid', BelongsToWhereUuidEquals::class, 'items'),
            Filter::custom('min_price', GreaterThanOrEqualTo::class, 'total_money'),
            Filter::custom('max_price', LessThanOrEqualTo::class, 'total_money'),
            Filter::exact('payment_type_uuid'),
            Filter::exact('payment_uuid', 'uuid'),
        ];
    }
}
