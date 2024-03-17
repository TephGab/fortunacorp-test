<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValideAccountTransfert implements Rule
{
    protected $balance;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($balance)
    {
        $this->balance = $balance;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value <= $this->balance;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Insufficient Account balance! please check amount.';
    }
}
