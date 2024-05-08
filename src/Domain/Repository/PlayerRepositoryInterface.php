<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Player;
use Symfony\Component\Uid\Uuid;

interface PlayerRepositoryInterface
{
    public function getUserByUsername(string $username): ?Player;

    public function save(Uuid $id, Player $player): void;

    public function getUserById(string $id): ?Player;

    public function flushAll(): void;

    public function delete(Uuid $id);
}
