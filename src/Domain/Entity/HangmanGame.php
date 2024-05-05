<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Letter;

class HangmanGame implements \JsonSerializable
{
    private Word $word;
    private int $remainingAttempts;
    private int $maxAttempts;
    private int $difficulty;

    public function __construct(string $word, int $maxAttempts, int $difficulty)
    {
        $this->word = new Word($word);
        $this->maxAttempts = $maxAttempts;
        $this->remainingAttempts = $maxAttempts;
        $this->difficulty = $difficulty;
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

        $alreadyGuessed = $this->word->isRevealedLetter($letter);
        if($alreadyGuessed === true){
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
        if(
            $this->remainingAttempts === $this->maxAttempts
            && $this->word->isRevealed() === false
        ){
            return sprintf(
                "Welcome to Hangman! The word has %s letters.\n%s\nYou have %s guesses left.",
                strlen($this->getWord()->getValue()),
                $this->getWord()->getDisplay(),
                $this->getRemainingAttempts()
            );
        }
        if ($this->remainingAttempts === 0) {
            return "You ran out of guesses. The word was: " . $this->word->getValue();
        } else {
            return sprintf("Congratulations! You guessed the word: %s", $this->word->getValue());
        }
    }

    public function requestHint(): bool
    {
        $revealed = $this->word->getRevealedLetters();
        $unrevealed = array_diff(str_split($this->word->getValue()), $revealed);
        if (empty($unrevealed)) {
            return false;
        }

        $toReveal = $unrevealed[array_rand($unrevealed)];
        $this->word->guess(new Letter($toReveal));
        $positions = [];

        for ($i = 0; $i < strlen($this->word->getValue()); $i++) {
            if ($this->word->getValue()[$i] === $toReveal) {
                $positions[] = $i;
            }
        }

        $numberOfGuessesUsed = count($positions);
        $this->remainingAttempts = max($this->remainingAttempts - $numberOfGuessesUsed, 0);

        return $numberOfGuessesUsed > 0;
     }

    public function isGameOver(): bool
    {
        return $this->remainingAttempts === 0 || $this->word->isRevealed();
    }

    public function getDifficulty(): int
    {
        return $this->difficulty;
    }

    public function jsonSerialize(): string
    {
        return json_encode(
            [
                'word' => json_encode($this->word),
                'remaining_attempts' => $this->remainingAttempts,
                'max_attempts' => $this->maxAttempts,
                'difficulty' => $this->difficulty
            ],
            JSON_PRETTY_PRINT
        );
    }
}
