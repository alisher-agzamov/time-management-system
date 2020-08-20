<?php

namespace App\Http\Requests\Api\V1;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class GetUserRequest extends FormRequest
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
        $user = User::find($this->getTargetUserId());

        return $user && $user->canRead($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
