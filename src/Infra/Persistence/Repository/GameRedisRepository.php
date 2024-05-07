<?php

declare(strict_types=1);

namespace App\Infra\Persistence\Repository;

use App\Domain\Entity\HangmanGame;
use App\Domain\Entity\Word;
use App\Domain\Repository\GameRepositoryInterface;
use App\UI\Http\Serializer\Normalizer\GameNormalizer;
use App\UI\Http\Serializer\Normalizer\GameNormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class GameRedisRepository implements GameRepositoryInterface
{
    private \Redis $redis;
    private SerializerInterface $serializer;

    public function __construct(\Redis $redis, SerializerInterface $serializer)
    {
        $this->redis = $redis;
        $this->serializer = $serializer;
    }

    /**
     * @throws \RedisException
     */
    public function save(HangmanGame $game): void
    {
        $data = $this->serializer->normalize($game);
        $content = json_encode($data, JSON_PRETTY_PRINT);
        $this->redis->set($game->getId()->toRfc4122(), $content);
    }

    public function find(string $id): ?HangmanGame
    {
        $data = json_decode($this->redis->get($id), true);
        $word = new Word($data['word']['value']);
        $word->setRevealedLetters($data['word']['revealed_letters']);

        $game = new HangmanGame(
            id: Uuid::fromString($data['id']),
            word: $word,
            maxAttempts: $data['max_attempts'],
            difficulty: $data['difficulty']
        );

        $game->setRemainingAttempts($data['remaining_attempts']);

        return $game;
    }
}
