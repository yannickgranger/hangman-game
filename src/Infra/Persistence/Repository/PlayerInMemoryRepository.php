<?php

namespace App\Infra\Persistence\Repository;

use App\Domain\Entity\Player;
use App\Domain\Repository\PlayerRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class PlayerInMemoryRepository implements PlayerRepositoryInterface
{
    private Collection $players;

    public function __construct()
    {
        $this->players = new ArrayCollection();
    }

    public function getUserByUsername(string $username): ?Player
    {
        foreach ($this->players as $player) {
            if ($player->getUsername() === $username) {
                return $player;
            }
        }

        return null;
    }

    public function getUserById(string $id): ?Player
    {
        return $this->players->get($id);
    }

    public function save(Uuid $id, Player $player): void
    {
        if (!$this->getUserByUsername($player->getUsername()) instanceof Player) {
            if (!$this->players->contains($player)) {
                $this->players->add($player);
            }
        }
    }

    public function flushAll(): void
    {
        $this->players = new ArrayCollection();
    }

    public function delete(Uuid $id)
    {
        $players = $this->players->filter(function (Player $player) use ($id) {
            return $player->getId() === $id;
        });

        dd($players);
    }
}
