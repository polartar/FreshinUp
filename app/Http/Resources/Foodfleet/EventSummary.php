<?php


namespace App\Http\Resources\Foodfleet;
use App\User;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\Company;
use App\Models\Foodfleet\Document;
use App\Models\Foodfleet\EventMenuItem;
use App\Enums\DocumentAssigned as DocumentAssignedEnum;
use App\Enums\DocumentStatus as DocumentStatusEnum;
use App\Enums\DocumentType as DocumentTypeEnum;
use App\Http\Resources\Foodfleet\Store\EventStore;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventSummary extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $host = Company::where('uuid', $this->host_uuid)->first();
        $customer = (object) [
            "owner" => null,
            "signed_contracts" => 0,
            "phone" => null,
            "email" => null
        ];
        if (!empty($host->users_id)) {
            $customer = User::where('id', $host->users_id)->first();
            $signed_contracts = Document::where('assigned_uuid', $customer->uuid)
                ->where('assigned_type', DocumentAssignedEnum::getDescription(DocumentAssignedEnum::USER))
                ->where('status_id', DocumentStatusEnum::APPROVED)
                ->count();
            $customer = (object) [
                "owner" => $customer->first_name . ' ' . $customer->last_name,
                "signed_contracts" => $signed_contracts,
                "phone" => $customer->mobile_phone,
                "email" => $customer->email
            ];
        }

        $stores = $this->stores;
        $total_fleet = sizeof($stores);
        $total_cost = 0;
        $total_commission = 0;
        foreach ($stores as $store) {
            $menuItems = EventMenuItem::where('store_uuid', $store->uuid)->get();
            $cost = $menuItems->sum('cost');
            $commission_type = $store->pivot->commission_type;
            $commission_rate = $store->pivot->commission_rate;
            if (empty($commission_type) || empty($commission_rate)) {
                $commission_type = $this->commission_type;
                $commission_rate = $this->commission_rate;
            }
            $commissions = $commission_rate;
            if ($commission_type == 2) {
                $commissions = $cost * $commission_rate / 100;
            }
            $total_cost = $total_cost + $cost;
            $total_commission = $total_commission + $commissions;
        }
        $financial = (object) [
            "total_fleet" => $total_fleet,
            "total_cost" => $total_cost,
            "amount_due" => $total_commission + $total_cost
        ];

        return [
            "customer" => $customer,
            "financial" => $financial
        ];
    }
}
