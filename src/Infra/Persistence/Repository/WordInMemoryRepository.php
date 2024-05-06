<?php

declare(strict_types=1);

namespace App\Infra\Persistence\Repository;

use App\Domain\Repository\WordRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Random\RandomException;

class WordInMemoryRepository implements WordRepositoryInterface
{
    /**
     * @var Collection<int, string>|ArrayCollection<int, string>
     */
    public Collection $words;

    public function __construct()
    {
        $this->words = new ArrayCollection([
            'ananas',
            'apple',
            'banana',
            'kiwi',
            'pineapple',
        ]);
    }

    /**
     * @throws RandomException
     */
    public function getRandomWord(): string
    {
        return $this->words->offsetGet(random_int(0, $this->words->count() - 1));
    }
}
