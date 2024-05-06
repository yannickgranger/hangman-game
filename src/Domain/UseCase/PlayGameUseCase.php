<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Entity\HangmanGame as Game;
use App\Domain\Repository\WordRepositoryInterface;
use App\Domain\ValueObject\Letter;

class PlayGameUseCase
{
    private WordRepositoryInterface $wordRepository;
    private int $maxAttempts;
    private int $defaultDifficulty;

    public function __construct(
        WordRepositoryInterface $wordRepository,
        int $maxAttempts,
        int $defaultDifficulty
    ) {
        $this->wordRepository = $wordRepository;
        $this->maxAttempts = $maxAttempts;
        $this->defaultDifficulty = $defaultDifficulty;
    }

    public function startGame(): Game
    {
        $word = $this->wordRepository->getRandomWord();
        return new Game(
            word: $word,
            maxAttempts: $this->maxAttempts,
            difficulty: $this->defaultDifficulty
        );
    }

    public function makeGuess(Game $game, Letter $letter): bool
    {
        return $game->playTurn($letter);
    }
}
