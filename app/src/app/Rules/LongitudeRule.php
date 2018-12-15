<?php

namespace App\Rules;

use App\ValueObjects\LongitudeEntity;
use Illuminate\Contracts\Validation\Rule;
use InvalidArgumentException;

/**
 * Class LongitudeRule.
 *
 * @package App\Rules
 */
class LongitudeRule implements Rule
{
    /**
     * @inheritdoc
     */
    public function passes($attribute, $value)
    {
        try {
            new LongitudeEntity($value);
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
        return __('validation.longitude');
    }
}
