<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CurrencyOperation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return in_array($value, ['buy', 'sell']);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The operationType parameter can be 'buy' or 'sell'.";
    }
}
