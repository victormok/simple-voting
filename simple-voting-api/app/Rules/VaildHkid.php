<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Ilex\Validation\HkidValidation\Helper;

class VaildHkid implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        //check hkid pattern

        $hkid = Helper::checkByString($value);

        if ($hkid->getReason())
            switch ($hkid->getReason()) {
                case \Ilex\Validation\HkidValidation\Enum\Reason::PattenError:
                    $fail('hkid patten error');
                    break;
                case \Ilex\Validation\HkidValidation\Enum\Reason::DigitError:
                    $fail('hkid digit error');
                    break;
            }
    }
}
