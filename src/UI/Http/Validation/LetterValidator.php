<?php

declare(strict_types=1);

namespace App\UI\Http\Validation;

/**
 * This validator is at api level and is more input sanitization than business related
 * That's why i choose to not have an interface (that should live in domain).
 * Or if you want this interface to exist, place it alongside validator
 */
class LetterValidator
{
    public function validateLetter(string $input): bool
    {
        $isValid = true;

        // throws if invalid

        return $isValid;
    }
}
