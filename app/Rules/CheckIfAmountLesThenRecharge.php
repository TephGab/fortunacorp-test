<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckIfAmountLesThenRecharge implements Rule
{
    protected $userInvestment;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($userInvestment)
    {
        $this->userInvestment = $userInvestment;
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
        return $value <= $this->userInvestment && $value != 0 && $value > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Amount can not exceed available agent recharge!';
    }
}
