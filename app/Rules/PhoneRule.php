<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class PhoneRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validatePhone(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^\+?[0-9]{10,15}$/', $value)) {
            $fail('Неверный формат телефона. Используйте международный формат (+79991234567) или 10-15 цифр');
        }
    }
}
