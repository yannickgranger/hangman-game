<?php

namespace App\Domain\Repository;

use App\Domain\Model\HangmanGame as Game;
use App\Domain\Model\Word;

interface GameServiceInterface
{
    public function startGame(Word $word): Game;
    public function guessLetter(Game $game, string $letter): string;
    public function isWinner(Game $game): bool;
}
