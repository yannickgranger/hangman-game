<?php

namespace App\Domain\Service;

use App\Domain\Entity\HangmanGame as Game;
use App\Domain\Entity\Word;

interface GameServiceInterface
{
    public function startGame(Word $word): Game;
    public function guessLetter(Game $game, string $letter): string;
    public function isWinner(Game $game): bool;
}
