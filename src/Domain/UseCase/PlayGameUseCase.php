<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Entity\HangmanGame as Game;
use App\Domain\Entity\Word;
use App\Domain\Repository\WordRepositoryInterface;
use App\Domain\ValueObject\Letter;
use Symfony\Component\Uid\Uuid;

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
            id: Uuid::v4(),
            word: new Word($word),
            maxAttempts: $this->maxAttempts,
            difficulty: $this->defaultDifficulty
        );
    }

    public function makeGuess(Game $game, Letter $letter): bool
    {
        return $game->playTurn($letter);
    }
}
