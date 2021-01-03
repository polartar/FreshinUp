<?php

namespace App\Http\Requests;

use App\Helpers\Permissions\MenuItemPermissions;
use Illuminate\Foundation\Http\FormRequest;

class MenuItemStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $currentUser = $this->user();
        $permissions = new MenuItemPermissions($currentUser);
        return $permissions->getPropertiesRules([]);
    }
}
