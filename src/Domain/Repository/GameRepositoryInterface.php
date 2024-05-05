<?php

namespace App\Domain\Repository;

use App\Domain\Entity\HangmanGame;
use Symfony\Component\Uid\Uuid;

interface GameRepositoryInterface
{
    public function find(string $id): ?HangmanGame;
    public function save(HangmanGame $game): Uuid;
}
