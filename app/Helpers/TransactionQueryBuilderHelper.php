<?php

namespace App\Helpers;

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
            Filter::custom('event_uuid', GreaterThanOrEqualTo::class, 'prepared_at'),

            Filter::partial('serial_number', 'serial_number'),
            Filter::exact('prepared_by_uuid', 'prepared_by_uuid'),
            Filter::custom('prepared_before', LessThanOrEqualTo::class, 'prepared_at'),
            Filter::exact('appraisal_status_uuid', 'appraisal_status_uuid'),
            Filter::exact('taken_in_by_uuid', 'taken_in_by_uuid'),
            Filter::exact('customer_uuid', 'customer_uuid'),
            Filter::exact('sell_status_uuid', 'sell_status_uuid'),
            Filter::exact('vehicle_uuid', 'vehicle_uuid'),
            Filter::exact('authorized_by_uuid', 'authorized_by_uuid'),
            Filter::exact('owning_location_id', 'owning_location_id'),
            Filter::exact('current_location_id', 'current_location_id'),
            Filter::custom('min_matrix_value', GreaterThanOrEqualTo::class, 'matrix_value'),
            Filter::custom('max_matrix_value', LessThanOrEqualTo::class, 'matrix_value'),
            Filter::custom('min_appraised_value', GreaterThanOrEqualTo::class, 'appraised_value'),
            Filter::custom('max_appraised_value', LessThanOrEqualTo::class, 'appraised_value'),
            Filter::custom('min_minimum_sell', GreaterThanOrEqualTo::class, 'minimum_sell'),
            Filter::custom('max_minimum_sell', LessThanOrEqualTo::class, 'minimum_sell'),
            Filter::custom('min_asking_price', GreaterThanOrEqualTo::class, 'asking_price'),
            Filter::custom('max_asking_price', LessThanOrEqualTo::class, 'asking_price'),
            Filter::custom('scheduled_after', GreaterThanOrEqualTo::class, 'scheduled_at'),
            Filter::custom('scheduled_before', LessThanOrEqualTo::class, 'scheduled_at'),
            Filter::custom('acquired_after', GreaterThanOrEqualTo::class, 'acquired_at'),
            Filter::custom('acquired_before', LessThanOrEqualTo::class, 'acquired_at'),
            Filter::custom('delivery_after', GreaterThanOrEqualTo::class, 'delivery_at'),
            Filter::custom('delivery_before', LessThanOrEqualTo::class, 'delivery_at'),
            Filter::custom('vin', VehicleFilter::class, 'vin'),
            Filter::custom('stock_number', VehicleFilter::class, 'stock_number')
        ];
    }
}
