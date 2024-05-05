<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Letter;
use InvalidArgumentException;

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
            throw new InvalidArgumentException("Word must only contain alphabetic characters");
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

        $this->revealedLetters[] = $letter->getValue();
        return str_contains($this->value, $letter->getValue());
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

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    /**
     * @param Word $word
     * @return array<string, string>
     */
    public static function toArray(Word $word): array
    {
        return  [
            'value' => $word->getValue(),
            'revealed_letters' => $word->getRevealedLetters(),
            'display' => $word->getDisplay()
        ];
    }
}
