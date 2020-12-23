<?php

namespace App\Http\Resources\Foodfleet\Company;

use FreshinUp\FreshBusForms\Http\Resources\Company\CompanyType as CompanyTypeResource;
use FreshinUp\FreshBusForms\Http\Resources\Team\Team as TeamResource;
use FreshinUp\FreshBusForms\Http\Resources\Company\CompanyStatus as CompanyStatusResource;
use FreshinUp\FreshBusForms\Models\Company\CompanyStatus;
use FreshinUp\FreshBusForms\Models\Company\CompanyType;
use Illuminate\Http\Resources\Json\JsonResource;
use FreshinUp\FreshBusForms\Http\Resources\TagsCollection as TagsResource;

class Company extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @param mixed $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $userResourceClass = config('fresh-bus-forms.resources.user');
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'created_at' => (string) $this->created_at,
            'status' => $this->status,
            'name' => $this->name,
            'address' => $this->address,
            'address2' => $this->address2,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'country' => $this->country,
            'website' => $this->website,
            'notes' => $this->notes,
            'members' => $userResourceClass::collection($this->whenLoaded('users')),
            'members_count' => $this->users_count,
            'teams' =>  TeamResource::collection($this->whenLoaded('teams')),
            'teams_count' => $this->teams_count,
            'tags' => new TagsResource($this->tags),
            'logo' => $this->logo,
            'admin' => new $userResourceClass($this->whenLoaded('admin')),
            'company_types' => CompanyTypeResource::collection($this->company_types()->get()),
            'type_id' => $this->type_id,
            'type' => new CompanyType($this->whenLoaded('type')),
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function with($request)
    {
        return [
            'meta' => [
                'types' => CompanyTypeResource::collection(CompanyType::get()),
                'statuses' => CompanyStatusResource::collection(CompanyStatus::get()),
            ],
        ];
    }
}
