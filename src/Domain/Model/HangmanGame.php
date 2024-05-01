<?php

declare(strict_types=1);

namespace App\Domain\Model;

class HangmanGame
{
    private Word $word;
    private int $remainingAttempts;

    public function __construct(string $word, int $maxAttempts)
    {
        $this->word = new Word($word);
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

    public function playTurn(Letter $letter): bool
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


    public function getFeedback(Letter $letter, bool $isCorrect): string
    {
        if ($isCorrect === true && $this->word->isRevealedLetter($letter)) {
            return sprintf("You guessed the letter %s.", $letter->getValue());
        } elseif ($isCorrect) {
            return sprintf("Good guess! The letter  %s is in the word.", $letter->getValue());
        } else {
            return "Incorrect guess. You have " . $this->getRemainingAttempts() . " guesses left.";
        }
    }

    public function getResultMessage(): string
    {
        if ($this->remainingAttempts === 0) {
            return "You ran out of guesses. The word was: " . $this->word->getValue();
        } else {
            return sprintf("Congratulations! You guessed the word: %s", $this->word->getValue());
        }
    }

    public function isGameOver(): bool
    {
        return $this->remainingAttempts === 0 || $this->word->isRevealed();
    }
}
