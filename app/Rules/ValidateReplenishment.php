<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateReplenishment implements Rule
{
    protected $funds;
    protected $revenues;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($funds, $revenues)
    {
        $this->funds = $funds;
        $this->revenues = $revenues;
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
        return $value <= ($this->funds + $this->revenues);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Amount can not be superior to funds and revenue!';
    }
}
