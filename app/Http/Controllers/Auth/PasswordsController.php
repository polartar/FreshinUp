<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Str;

class PasswordsController extends Controller
{
    use ResetsPasswords;

    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.

        if ($request->expectsJson()) {
            if ($response == Password::PASSWORD_RESET) {
                return response()->json(['message' => trans($response), 'status' => true,]);
            } else {
                return response()->json(['message' => trans($response), 'status' => false,], 401);
            }
        }

        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($request, $response)
                    : $this->sendResetFailedResponse($request, $response);
    }


    /**
     * @param \App\User $user
     * @param $password
     */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Omit triggering this event so that the listener in Bus won't fire
        // see https://github.com/FreshinUp/fresh-bus-forms/blob/master/src/Listeners/UserPasswordReset.php
        // event(new PasswordReset($user));

        $this->guard()->login($user);
    }
}
