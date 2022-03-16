<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GpsFormat implements Rule
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
        if (strpos($value, ',', ) === false) return false;
        if (strpos($value, '.') === false) return false;

        $coordinates = array_map('trim', explode(',', $value));
        if (count($coordinates) !== 2) return false;

        return is_numeric($coordinates[0]) && is_numeric($coordinates[1]);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid location format.';
    }
}
