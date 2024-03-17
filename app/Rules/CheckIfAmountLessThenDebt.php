<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckIfAmountLessThenDebt implements Rule
{
    protected $userDebt;
    protected $sumOfPendingAmounts;
    protected $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($userDebt, $sumOfPendingAmounts)
    {
        $this->userDebt = $userDebt;
        $this->sumOfPendingAmounts = $sumOfPendingAmounts;
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
        if (!is_numeric($value)) {
            $this->setMessage('The amount must be a number.');
        } elseif ($value <= 0) {
            $this->setMessage('The amount must be a positive number.');
        } elseif ($value > $this->userDebt) {
            $this->setMessage('The amount cannot exceed the current debt.');
        } elseif ($value > ($this->userDebt - $this->sumOfPendingAmounts)) {
            $this->setMessage('Pending deposit in process. please enter a lower amount');
        } else {
            return true;
        }
        return false;
        // return $value <= $this->userDebt && $value != 0 && $value > 0;
    }

        /**
     * Set a custom validation error message.
     *
     * @param  string  $message
     * @return void
     */
    protected function setMessage($message)
    {
        $this->message = $message;
    }    

     /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message ?? 'Validation failed please try again.';
    }

}
