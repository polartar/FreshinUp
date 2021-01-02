<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class AuthUser extends Controller
{

    public function currentUser()
    {
        $authUser = Auth::user();
        if (!$authUser) {
            return response()->json(['status' => 'Unauthorized'], 401);
        }
        $User = config('fresh-bus-forms.models.user');
        $user = QueryBuilder::for($User::where('id', $authUser->id))
            ->with(['user_type', 'media', 'manager'])
            ->allowedIncludes(['teams.users'])
            ->first();

        $resourceClass = config('fresh-bus-forms.resources.current_user');
        return new $resourceClass($user);
    }
}
