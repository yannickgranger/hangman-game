<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Exception\CannotJoinGameException;
use App\Domain\Exception\GameFullException;
use App\Domain\ValueObject\Letter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class HangmanGame implements \JsonSerializable
{
    private Uuid $id;
    private Word $word;
    private int $remainingAttempts;
    private int $maxAttempts;
    private int $difficulty;
    private int $maxPlayers;
    private bool $multiplayer;
    private Collection $players;

    public function __construct(
        Uuid $id,
        Word $word,
        int $maxAttempts,
        int $difficulty,
        ?bool $multiplayer = false,
        ?int $maxPlayers = 2
    ) {
        $this->id = $id;
        $this->word = $word;
        $this->maxAttempts = $maxAttempts;
        $this->remainingAttempts = $maxAttempts;
        $this->difficulty = $difficulty;
        $this->multiplayer = $multiplayer;
        $this->maxPlayers = $maxPlayers;
        $this->players = new ArrayCollection();
    }

    public function playTurn(Letter $letter, ?Player $player = null): ?bool
    {
        if ($this->isGameOver()) {
            return null;
        }

        $alreadyGuessed = $this->word->isRevealedLetter($letter);
        if ($alreadyGuessed === true) {
            return false;
        }
        $isCorrect = $this->word->guess($letter);
        if (!$isCorrect) {
            --$this->remainingAttempts;
        }

        if ($this->isMultiplayer()) {
            $player->updateGuess($this->word, $letter); // attempts are shared but stats are separated
        }

        return $isCorrect;
    }

    public function getFeedback(Letter $letter, ?bool $isCorrect = null): string
    {
        if ($isCorrect) {
            return
                sprintf('Good guess! The letter %s is in the word.', $letter->getValue()).PHP_EOL
                .$this->word->getDisplay()
            ;
        } elseif ($isCorrect === null) {
            return $this->isGameOver() ? 'Game is over' : throw new \Exception('Invalid state IN '.__METHOD__);
        } else {
            return 'Incorrect guess. You have '.$this->getRemainingAttempts().' guesses left.';
        }
    }

    public function getResultMessage(): string
    {
        // game start
        if (
            $this->remainingAttempts === $this->maxAttempts
            && $this->word->isRevealed() === false
        ) {
            return sprintf(
                "Welcome to Hangman! The word has %s letters.\n%s\nYou have %s guesses left.",
                strlen($this->getWord()->getValue()),
                $this->getWord()->getDisplay(),
                $this->getRemainingAttempts()
            );
        }

        // game is over: loose else win cases
        if ($this->remainingAttempts === 0) {
            return 'You ran out of guesses. The word was: '.$this->word->getValue();
        } else {
            return sprintf(
                'Congratulations! You guessed the word: %s', $this->word->getValue()
            );
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

        for ($i = 0; $i < strlen($this->word->getValue()); ++$i) {
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

    public function join(Player $player): string
    {
        if ($this->players->contains($player)) {
            throw new CannotJoinGameException(sprintf('Player %s already joined the game.', $player->getUsername()));
        }

        if ($this->players->count() + 1 > $this->maxPlayers) {
            throw new GameFullException();
        }

        $this->players->add($player);

        $msg = $this->getResultMessage();
        foreach ($this->players->getIterator() as $player) {
            $msg .= PHP_EOL
                .sprintf(
                    'Player %s has joined the game!', $player->getUsername()
                );
        }

        return $msg;
    }

    public function jsonSerialize(): mixed
    {
        return json_encode(
            [
                'word' => $this->word->toArray(),
                'remaining_attempts' => $this->remainingAttempts,
                'max_attempts' => $this->maxAttempts,
                'difficulty' => $this->difficulty,
            ],
            JSON_PRETTY_PRINT
        );
    }

    public function getDifficulty(): int
    {
        return $this->difficulty;
    }

    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }

    public function setRemainingAttempts(int $remainingAttempts): void
    {
        $this->remainingAttempts = $remainingAttempts;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getWord(): Word
    {
        return $this->word;
    }

    public function getRemainingAttempts(): int
    {
        return $this->remainingAttempts;
    }

    public function getMaxPlayers(): int
    {
        return $this->maxPlayers;
    }

    public function setMaxPlayers(int $maxPlayers): void
    {
        $this->maxPlayers = $maxPlayers;
    }

    public function isMultiplayer(): bool
    {
        return $this->multiplayer;
    }

    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function setPlayers(Collection $players): void
    {
        $this->players = $players;
    }
}
