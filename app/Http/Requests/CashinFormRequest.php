<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashinFormRequest extends FormRequest
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
            'provider_name' => 'required',
            'provider_phone' => 'required|min:10|max:15',
            'provider_email' => 'required',
            'provider_address' => 'required',
            'amount' => 'required|min:3|starts_with:1,2,3,4,5,6,7,8,9',
            'status' => 'required',
            'type' => 'required',
            'operation' => 'required'
        ];
    }
}
