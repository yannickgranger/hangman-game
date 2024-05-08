<?php

namespace App\Infra\Persistence\Repository;

use App\Domain\Entity\Player;
use App\Domain\Repository\PlayerRepositoryInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class PlayerRedisRepository implements PlayerRepositoryInterface
{
    private \Redis $redis;
    private SerializerInterface $serializer;

    private array $userRegistry = [];

    public function __construct(\Redis $redis, SerializerInterface $serializer)
    {
        $this->redis = $redis;
        $this->serializer = $serializer;
        $this->userRegistry = [];
    }

    public function getUserById(string $id): ?Player
    {
        $normalizedPlayer = $this->redis->get($id);
        if ($normalizedPlayer === false) {
            return null;
        }
        $normalizedPlayer = json_decode($normalizedPlayer, true);

        return new Player(
            id: Uuid::fromString($normalizedPlayer['id']),
            username: $normalizedPlayer['username']
        );
    }

    public function getUserByUsername(string $username): ?Player
    {
        if (!$this->usernameExists($username)) {
            return null;
        }

        $registry = $this->getUserRegistry();
        $userId = array_filter($registry, function ($record) use ($username) {
            return strtolower(trim($record)) === strtolower(trim($username));
        });

        $id = array_key_first($userId);

        return $this->getUserById($id);
    }

    private function usernameExists(string $username): bool
    {
        $registry = $this->getUserRegistry();

        return in_array($username, $registry);
    }

    private function saveRegistry(array $userRegistry): void
    {
        $this->redis->set('user-registry', json_encode($userRegistry, JSON_PRETTY_PRINT));
    }

    private function getUserRegistry(): array
    {
        return
            $this->redis->get('user-registry') !== false
                ? json_decode($this->redis->get('user-registry'), true)
                : $this->userRegistry;
    }

    public function save(Uuid $id, Player $player): void
    {
        $registry = $this->getUserRegistry();
        if (!$this->usernameExists($player->getUsername())) {
            $registry[$id->toRfc4122()] = $player->getUsername();
            $this->redis->set($id->toRfc4122(), json_encode($this->serializer->normalize($player), true));
            $this->saveRegistry($registry);

            return;
        }

        throw new \LogicException(sprintf('Username already exists: %s', $player->getUsername()));
    }

    public function flushAll(): void
    {
        $this->redis->flushAll();
    }

    public function delete(Uuid $id)
    {
        $this->redis->del($id->toRfc4122());
        $registry = $this->getUserRegistry();
        unset($registry[$id->toRfc4122()]);
        $this->saveRegistry($registry);
    }
}
