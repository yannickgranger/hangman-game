<?php

namespace App\Domain\Repository;

use App\Domain\Entity\HangmanGame;

interface GameRepositoryInterface
{
    public function find(string $id): ?HangmanGame;
    public function save(HangmanGame $game): void;
}
