<?php

namespace App\Helpers;

use App\Filters\Transaction\BelongsToWhereUuidEquals;
use App\Filters\Transaction\CategoryUuid;
use App\Filters\Transaction\DeviceUuid;
use App\Filters\Transaction\PaymentTypeUuid;
use App\Filters\Transaction\StaffUuid;
use App\Filters\Transaction\SupplierUuid;
use FreshinUp\FreshBusForms\Filters\GreaterThanOrEqualTo;
use FreshinUp\FreshBusForms\Filters\LessThanOrEqualTo;
use Spatie\QueryBuilder\Filter;

class TransactionQueryBuilderHelper
{
    /**
     * Return an array of allowed filters for Transactions
     *
     * @param $prefix String
     * @return array
     */
    public static function getTransactionFilters()
    {
        return [
            Filter::exact('event_uuid'),
            Filter::exact('store_uuid'),
            Filter::custom('supplier_uuid', SupplierUuid::class),
            Filter::exact('host_uuid', 'event.host_uuid'),
            Filter::custom('date_after', GreaterThanOrEqualTo::class, 'square_created_at'),
            Filter::custom('date_before', LessThanOrEqualTo::class, 'square_created_at'),
            Filter::custom('event_tag_uuid', BelongsToWhereUuidEquals::class, 'event.eventTags'),
            Filter::exact('location_uuid', 'event.location_uuid'),
            Filter::exact('customer_uuid'),
            Filter::custom('staff_uuid', StaffUuid::class),
            Filter::custom('device_uuid', DeviceUuid::class),
            Filter::custom('category_uuid', CategoryUuid::class),
            Filter::custom('item_uuid', BelongsToWhereUuidEquals::class, 'items'),
            Filter::custom('min_price', GreaterThanOrEqualTo::class, 'total_money'),
            Filter::custom('max_price', LessThanOrEqualTo::class, 'total_money'),
            Filter::custom('payment_type_uuid', PaymentTypeUuid::class),
            Filter::custom('payment_uuid', BelongsToWhereUuidEquals::class, 'payments'),
        ];
    }
}
