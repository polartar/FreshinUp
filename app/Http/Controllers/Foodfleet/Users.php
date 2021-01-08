<?php

namespace App\Http\Controllers\Foodfleet;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\User;
use FreshinUp\FreshBusForms\Enums\UserLevel;
use Illuminate\Http\Request;

class Users extends Controller
{
    // TODO tests
    public function storeCustomerOrSupplier(Request $request)
    {
        // sending all because action is filtering payload
        if (!$request->has('type')) {
            $request->merge([
                'type' => UserType::CUSTOMER
            ]);
        }
        $rules = [
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile_phone' => 'sometimes',
            'password' => 'required|confirmed',
            'type' => 'required|in:' . implode(',', [UserType::CUSTOMER, UserType::SUPPLIER])
        ];
        $this->validate($request, $rules);
        $payload = $request->only(array_keys($rules));
        $user = User::create(array_merge($payload, [
            'status' => UserStatus::PENDING_REVIEW,
            'level' => UserLevel::COMPANY_MEMBER // legacy ?
        ]));
        $Resource = config('fresh-bus-forms.resources.user');
        return new $Resource($user);
    }

    // TODO: update
    // see https://github.com/FreshinUp/fresh-bus-forms/issues/871
}
