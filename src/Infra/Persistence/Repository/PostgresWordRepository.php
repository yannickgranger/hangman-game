<?php

declare(strict_types=1);

namespace App\Infra\Persistence\Repository;

use App\Domain\Repository\WordRepositoryInterface;

class PostgresWordRepository implements WordRepositoryInterface
{
    public function getRandomWord(): string
    {
        // TODO: Implement getRandomWord() method.
    }
}
