<?php

declare(strict_types=1);

namespace App\Domain;

class Word
{
    private string $value;
    private array $revealedLetters;

    public function __construct(string $value)
    {
        $this->value = $value;
        $this->revealedLetters = [];
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getRevealedLetters(): array
    {
        return $this->revealedLetters;
    }

    public function guess(string $letter): bool
    {
        if (in_array($letter, $this->revealedLetters)) {
            return false;
        }

        $this->revealedLetters[] = $letter;
        return str_contains($this->value, $letter);
    }

    public function isRevealed(): bool
    {
        return count(array_diff(array_unique(str_split($this->value)), $this->revealedLetters)) === 0;
    }

    public function getDisplay(): string
    {
        $display = '';
        foreach (str_split($this->value) as $char) {
            $display .= in_array($char, $this->revealedLetters) ? $char : '_';
        }
        return $display;
    }
}
