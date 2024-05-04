<?php

declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Entity\HangmanGame;
use App\Domain\Repository\WordRepositoryInterface;

class InitializeGameUseCase
{
    private WordRepositoryInterface $wordRepository;
    private int $maxAttempts;

    public function __construct(WordRepositoryInterface $wordRepository, int $maxAttempts)
    {
        $this->wordRepository = $wordRepository;
        $this->maxAttempts = $maxAttempts;
    }

    public function execute(): HangmanGame
    {
        return new HangmanGame($this->wordRepository->getRandomWord(), $this->maxAttempts, 5);
    }
}
