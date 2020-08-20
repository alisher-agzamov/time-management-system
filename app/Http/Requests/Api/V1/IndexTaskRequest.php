<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class IndexTaskRequest extends FormRequest
{
    /**
     * Get target user ID
     * @return \Illuminate\Routing\Route|object|string|null
     */
    public function getTargetUserId()
    {
        return $this->get('user_id', $this->user()->id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // If user_id is not empty it means admin views user's tasks
        // and we need to check permissions
        if(!empty($this->get('user_id'))) {
            return $this->user()->hasRole('admin');
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'integer|exists:App\User,id',
        ];
    }
}
