<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Entity\HangmanGame;
use App\Domain\Repository\GameRepositoryInterface;
use App\Domain\Repository\WordRepositoryInterface;

class CreateGameUseCase implements CreateGameUseCaseInterface
{
    private GameRepositoryInterface $gameRepository;
    private WordRepositoryInterface $wordRepository;

    public function __construct(GameRepositoryInterface $gameRepository, WordRepositoryInterface $wordRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->wordRepository = $wordRepository;
    }

    public function execute(int $maxAttempts, ?int $difficulty)
    {
        $hangmanGame = new HangmanGame(
            word: $this->wordRepository->getRandomWord(),
            maxAttempts: $maxAttempts,
            difficulty: $difficulty
        );

        $this->gameRepository->save($hangmanGame);

        return $hangmanGame;
    }
}