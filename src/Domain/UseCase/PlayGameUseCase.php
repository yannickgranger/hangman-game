<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Repository\WordRepositoryInterface;
use App\Domain\Model\HangmanGame as Game;

class PlayGameUseCase
{
    private WordRepositoryInterface $wordRepository;
    private int $maxAttempts;

    public function __construct(WordRepositoryInterface $wordRepository, int $maxAttempts)
    {
        $this->wordRepository = $wordRepository;
        $this->maxAttempts = $maxAttempts;
    }

    public function startGame(): Game
    {
        $word = $this->wordRepository->getRandomWord();
        return new Game($word, $this->maxAttempts);
    }

    public function makeGuess(Game $game, string $letter): bool
    {
        return $game->playTurn($letter);
    }
}
