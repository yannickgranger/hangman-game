<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Entity\HangmanGame;
use App\Domain\ValueObject\Letter;

class GuessLetterUseCase
{
    private HangmanGame $game;

    public function __construct(HangmanGame $game)
    {
        $this->game = $game;
    }

    public function execute(Letter $letter): bool
    {
        return $this->game->playTurn($letter);
    }
}