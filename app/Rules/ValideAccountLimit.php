<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValideAccountLimit implements Rule
{
    protected $balance;
    protected $category;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($balance, $category)
    {
        $this->balance = $balance;
        $this->category = $category;
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
        if($this->category == 'business'){
            return $value + $this->balance <= 100000;
        }else{
            return $value + $this->balance <= 75000;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Account can not receive this amount. limit reached!';
    }
}
