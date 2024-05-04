<?php

declare(strict_types=1);

namespace App\Tests\__Mock__;

use App\Domain\Repository\WordRepositoryInterface;

class WordInMemoryRepository implements WordRepositoryInterface
{
    public function getRandomWord(): string
    {
        return 'ananas';
    }
}
