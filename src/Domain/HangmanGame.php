<?php

declare(strict_types=1);

namespace App\Domain;

class HangmanGame
{
    private Word $word;
    private int $maxAttempts;
    private int $remainingAttempts;

    public function __construct(string $word, int $maxAttempts)
    {
        $this->word = new Word($word);
        $this->maxAttempts = $maxAttempts;
        $this->remainingAttempts = $maxAttempts;
    }

    public function getWord(): Word
    {
        return $this->word;
    }

    public function getRemainingAttempts(): int
    {
        return $this->remainingAttempts;
    }

    public function playTurn(string $letter): bool
    {
        if ($this->isGameOver()) {
            return false;
        }

        $isCorrect = $this->word->guess($letter);
        if (!$isCorrect) {
            $this->remainingAttempts--;
        }
        return $isCorrect;
    }

    public function isGameOver(): bool
    {
        return $this->remainingAttempts === 0 || $this->word->isRevealed();
    }
}
