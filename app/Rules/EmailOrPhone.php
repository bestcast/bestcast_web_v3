<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailOrPhone implements Rule
{
    public function passes($attribute, $value)
    {
        // Check if the value is either a valid email or a numeric value
        return filter_var($value, FILTER_VALIDATE_EMAIL) || (is_numeric($value) && (strlen($value)==10));
    }

    public function message()
    {
        return 'Please enter valid email or mobile.';
    }
}
