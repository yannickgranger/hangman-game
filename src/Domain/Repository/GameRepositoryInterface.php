<?php

namespace App\Domain\Repository;

use App\Domain\Entity\HangmanGame;

interface GameRepositoryInterface
{
    public function save(HangmanGame $game): void;
}
