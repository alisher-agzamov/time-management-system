<?php

namespace App\Http\Requests\Api\V1;

use App\Exceptions\AccessDeniedException;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Is needed to update email
     * @var bool
     */
    public $isNeedToUpdateEmail = false;

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

        if(!$user->canBeEditedBy($this->user())) {
            return false;
        }

        // Do not to check edit role permission if the role was not sent
        if(empty($this->get('role'))) {
            return true;
        }

        return $user->roleCanBeEditedBy($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'      => 'required|string|max:255',
            'role'      => 'nullable|string|exists:roles,name',
            'preferred_working_hour_per_day'    => 'required|integer|gt:0|lt:1440', //max 23:59 hours in minutes
        ];

        // If admin or manager edits someone profile then email field is mandatory and it can be changed
        if($this->route('id') != 'me'
            && $this->user()->hasRole(['admin', 'manager'])) {

            $rules['email'] = [
                'required',
                Rule::unique('users')->ignore($this->getTargetUserId()),
            ];

            $this->isNeedToUpdateEmail = true;
        }

        return $rules;
    }
}
