<?php

namespace App\Rules;

use App\ValueObjects\LatitudeEntity;
use Illuminate\Contracts\Validation\Rule;
use InvalidArgumentException;

/**
 * Class LatitudeRule.
 *
 * @package App\Rules
 */
class LatitudeRule implements Rule
{
    /**
     * @inheritdoc
     */
    public function passes($attribute, $value)
    {
        try {
            new LatitudeEntity($value);
            return true;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function message()
    {
        return __('validation.latitude');
    }
}
