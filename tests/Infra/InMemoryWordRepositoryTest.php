<?php

declare(strict_types=1);

namespace App\Tests\Infra;

use App\Domain\Repository\WordRepositoryInterface;
use App\Infra\Persistence\Repository\InMemoryWordRepository;
use PHPUnit\Framework\TestCase;
use Random\RandomException;

class InMemoryWordRepositoryTest extends TestCase
{
    private WordRepositoryInterface $wordRepository;
    protected function setUp(): void
    {
        parent::setUp();
        $this->wordRepository = new InMemoryWordRepository();
    }

    /**
     * @throws RandomException
     */
    public function testItGetRandomWords(): void
    {
        self::assertTrue(
            in_array(
                $this->wordRepository->getRandomWord(),
                [
                    'banana',
                    'apple',
                    'kiwi',
                    'pineapple',
                ]
            ),
        );
    }
}
