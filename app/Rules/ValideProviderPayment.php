<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValideProviderPayment implements Rule
{
    protected $claim;
    protected $depts;
    protected $funds;
    protected $revenues;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($claim, $depts, $funds, $revenues)
    {
        $this->claim = $claim;
        $this->depts = $depts;
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
        return $value <= $this->claim && $value <= $this->depts && $value <= ($this->funds + $this->revenues);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Amount can not be superior to provider claim or system depts, or funds!';
    }
}
