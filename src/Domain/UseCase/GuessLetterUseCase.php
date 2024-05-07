<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Entity\HangmanGame;
use App\Domain\Repository\GameRepositoryInterface;
use App\Domain\ValueObject\Letter;

class GuessLetterUseCase
{
    private GameRepositoryInterface $gameRepository;

    public function __construct(GameRepositoryInterface $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function execute(HangmanGame $game, Letter $letter): bool
    {
        $guess = $game->playTurn($letter);
        $this->gameRepository->save($game);
        return $guess;
    }
}
