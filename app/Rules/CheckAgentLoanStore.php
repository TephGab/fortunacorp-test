<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckAgentLoanStore implements Rule
{
    protected $agentDebt;
    protected $message;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($agentDebt)
    {
        $this->agentDebt = $agentDebt;
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
        } else {
            return true;
        }
        return false;
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
