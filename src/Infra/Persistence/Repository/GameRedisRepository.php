<?php

declare(strict_types=1);

namespace App\Infra\Persistence\Repository;

use App\Domain\Entity\HangmanGame;
use App\Domain\Repository\GameRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class GameRedisRepository implements GameRepositoryInterface
{
    private \Redis $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @throws \RedisException
     */
    public function save(HangmanGame $game): Uuid
    {
        $key = Uuid::v4();
        $content = json_encode($game, JSON_PRETTY_PRINT);
        $this->redis->set($key->toRfc4122(), $content);
        return $key;
    }

    public function find(string $id): ?HangmanGame
    {
        return json_decode($this->redis->get($id));
    }
}
