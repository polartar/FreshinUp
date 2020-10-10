<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;

class TemplateObserver
{
    public function creating($template)
    {
        /** @var \App\User $user */
        $user = Auth::user();
        if ($user) {
            $template->updated_by_uuid = $user->uuid;
        }
    }


    public function updating($template)
    {
        /** @var \App\User $user */
        $user = Auth::user();
        if ($user) {
            $template->updated_by_uuid = $user->uuid;
        }
    }
}
