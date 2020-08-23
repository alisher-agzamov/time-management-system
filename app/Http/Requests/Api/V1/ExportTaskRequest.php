<?php

namespace App\Http\Requests\Api\V1;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class ExportTaskRequest extends FormRequest
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
            'user_id'   => 'integer|exists:App\User,id',
            'date_from' => 'required|date',
            'date_to'   => 'required|date',
        ];
    }
}
