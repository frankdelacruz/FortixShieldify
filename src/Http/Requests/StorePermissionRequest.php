<?php

namespace Fortix\Shieldify\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules()
    {
        return [
            'role_id' => 'required|exists:roles,id',
            'module_id' => 'required|exists:modules,id',
            'permissions' => 'required|array', // Adjust if your structure is different
        ];
    }
}
