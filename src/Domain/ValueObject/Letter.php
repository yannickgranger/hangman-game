<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

/*
 * Value Objects are immutable
 */

use App\Domain\Exception\InvalidGuessException;

class Letter
{
    private string $value;
    public function __construct(string $value)
    {
        if (strlen($value) !== 1 || !ctype_alpha($value)) {
            throw new InvalidGuessException("Invalid letter. Please provide a single alphabetic character.");
        }

        $this->value = strtolower($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}