<?php

namespace App\Domain\Repository;

interface WordRepositoryInterface
{
    public function getRandomWord(): string;
}
