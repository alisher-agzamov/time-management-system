<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
        return [
            'title'         => 'required|max:255',
            'description'   => 'required',
            'date'          => 'required|date',
            'duration'      => 'required|integer|gt:0|lt:1440', //max 23:59 hours in minutes
            'user_id'       => 'integer|exists:App\User,id',
        ];
    }
}
