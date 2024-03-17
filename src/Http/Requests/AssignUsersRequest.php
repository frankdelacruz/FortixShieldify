<?php

namespace Fortix\Shieldify\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignUsersRequest extends FormRequest
{
    public function authorize()
    {
        // Assuming any authenticated user can assign roles, otherwise, implement your authorization logic here
        return true;
    }

    public function rules()
    {
        return [
            'userIds' => ['required', 'array'],
            'userIds.*' => ['exists:users,id'], // Ensure each user ID exists in the users table
        ];
    }
}
