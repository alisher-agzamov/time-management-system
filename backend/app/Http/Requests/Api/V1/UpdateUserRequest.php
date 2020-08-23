<?php

namespace App\Http\Requests\Api\V1;

use App\Exceptions\AccessDeniedException;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get target user ID
     * @return \Illuminate\Routing\Route|object|string|null
     */
    public function getTargetUserId()
    {
        if($this->route('id') == 'me') {
            return $this->user()->id;
        }

        return $this->route('id');
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(!$user = User::find($this->getTargetUserId())) {
            return false;
        }

        if(!$user->canEdit($this->user())) {
            return false;
        }

        // Do not to check edit role permission if the role was not sent
        if(empty($this->get('role'))) {
            return true;
        }

        return $user->canEditRole($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'      => 'required|string|max:255',
            /*'email'     => [
                'required',
                Rule::unique('users')->ignore($this->getTargetUserId()),
            ],*/
            'role'      => 'nullable|string',
            'preferred_working_hour_per_day'    => 'required|integer|gt:0|lt:1440', //max 23:59 hours in minutes
        ];
    }
}
