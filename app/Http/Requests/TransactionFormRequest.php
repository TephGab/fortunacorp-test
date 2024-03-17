<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionFormRequest extends FormRequest
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
            'sender_name' => 'required',
            'sender_phone' => 'required|min:10|max:15',
            'receiver_name' => 'required',
            'receiver_phone' => 'required|different:sender_phone|min:10|max:15',
            'sender_amount' => 'required|min:3|starts_with:1,2,3,4,5,6,7,8,9',
            'receiver_amount' => 'required|min:3|starts_with:1,2,3,4,5,6,7,8,9',
            'type' => 'required',
            'operation' => 'required'
        ];
    }

      /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'amount.starts_with' => 'Amount can not start with 0',
        ];
    }
}
