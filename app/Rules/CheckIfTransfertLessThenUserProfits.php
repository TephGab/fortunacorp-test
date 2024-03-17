<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;

class CheckIfTransfertLessThenUserProfits implements Rule
{

    protected $userBalance;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($userBalance)
    {
        $this->userBalance = $userBalance;
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
        // Check if the value is less than or equal to the user's balance.
        return $value <= $this->userBalance && $value != 0 && $value > 0;
    }
    

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Amount can not exceed available balance!';
    }
}
