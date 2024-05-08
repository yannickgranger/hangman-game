<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Letter;

class Word implements \JsonSerializable
{
    private string $value;

    /**
     * @var array<string>
     */
    private array $revealedLetters;

    public function __construct(string $value)
    {
        if (!ctype_alpha($value)) {
            throw new \InvalidArgumentException('Word must only contain alphabetic characters');
        }
        $this->value = $value;
        $this->revealedLetters = [];
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return array<string>
     */
    public function getRevealedLetters(): array
    {
        return $this->revealedLetters;
    }

    public function isRevealedLetter(Letter $letter): bool
    {
        return in_array($letter->getValue(), $this->revealedLetters);
    }

    public function guess(Letter $letter): bool
    {
        if (in_array($letter->getValue(), $this->revealedLetters)) {
            return false;
        }

        if (str_contains($this->value, $letter->getValue())) {
            $this->revealedLetters[] = $letter->getValue();

            return true;
        }

        return false;
    }

    public function isRevealed(): bool
    {
        return count(array_diff(array_unique(str_split($this->value)), $this->revealedLetters)) === 0;
    }

    public function getDisplay(): string
    {
        $display = '';
        foreach (str_split($this->value) as $char) {
            $display .= in_array(strtolower($char), $this->revealedLetters) ? $char : '_';
        }

        return $display;
    }

    public function setRevealedLetters(array $revealedLetters): void
    {
        $this->revealedLetters = $revealedLetters;
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'value' => $this->getValue(),
            'revealed_letters' => $this->getRevealedLetters(),
            'display' => $this->getDisplay(),
        ];
    }

    public function jsonSerialize(): string
    {
        return json_encode($this, JSON_PRETTY_PRINT);
    }
}
