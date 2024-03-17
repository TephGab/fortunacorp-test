<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserFormRequest extends FormRequest
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
            'code' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|min:10|max:15',
            'email' => 'required|email',
            'address' => 'required',
            'location' => 'required',
            'default_percentage' => 'required',
            'limit_min' => 'required',
            'limit_max' => 'required',
            'id_type' => 'required',
            'id_number' => 'required',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ];
    }
}