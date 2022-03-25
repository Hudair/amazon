<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class License implements Rule
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
        $len = strlen($value);

        return $len > 18 && $len < 37 && substr_count($value, '-') > 2;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('app.invalid_license_key');
    }
}
