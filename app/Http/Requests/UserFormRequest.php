<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
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
            'code' => 'required|unique:users',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|min:10|max:15|unique:users',
            'email' => 'required|email|unique:users',
            'address' => 'required',
            'location' => 'required',
            'percentage' => 'required',
            'reference' => 'required',
            'password' => 'required|min:4',
            'limit_min' => 'required',
            'limit_max' => 'required',
            'id_type' => 'required',
            'id_number' => 'required',
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ];
    }

    /**
 * Get the error messages for the defined validation rules.
 *
 * @return array
 */
// public function messages()
// {
    // return [
    //     'phone.starts_with' => 'Phone can not start with 0',
    // ];
// }
}
