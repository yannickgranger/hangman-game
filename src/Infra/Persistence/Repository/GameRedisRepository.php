<?php

declare(strict_types=1);

namespace App\Infra\Persistence\Repository;

use App\Domain\Entity\HangmanGame;
use App\Domain\Repository\GameRepositoryInterface;

class GameRedisRepository implements GameRepositoryInterface
{
    public function save(HangmanGame $game): void
    {
        // TODO: Implement save() method.
    }
}
