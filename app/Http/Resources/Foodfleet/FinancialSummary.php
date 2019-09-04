<?php

namespace App\Http\Resources\Foodfleet;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FinancialSummary extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $transactions = $this->collection;

        $getLabelValueArray = function(Collection $collection) {
            return $collection->map(function($model) {
                return [
                    'label' => $model->name,
                    'value' => 0
                ];
            });
        };
        $arrivals = $getLabelValueArray(VehicleStatus::all());
        $conditions = $getLabelValueArray(StockStatus::all());
        $brands = $getLabelValueArray(Make::all());

        $incrementCount = function(Collection $collection, string $label) {
            $collection->transform(function($element) use ($label){
                if ($element['label'] == $label) $element['value']++;

                return $element;
            });
        };

        foreach ($vehicles as $vehicle) {
            if (isset($vehicle->vehicleStatus)) {
                $incrementCount($arrivals, $vehicle->vehicleStatus->name);
            }

            if (isset($vehicle->stockStatus)) {
                $incrementCount($conditions, $vehicle->stockStatus->name);
            }

            if (isset($vehicle->make)) {
                $incrementCount($brands, $vehicle->make->name);
            }
        }

        return [
            'data' => [
                'arrivals' => $arrivals,
                'conditions' => $conditions,
                'brands' => $brands
            ]
        ];

    }
}
