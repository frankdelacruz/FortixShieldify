<?php

namespace Fortix\Shieldify\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize()
    {
        // Your authorization logic here
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name', // Adjust the table name as needed
            'description' => 'nullable|string',
        ];
    }
}
