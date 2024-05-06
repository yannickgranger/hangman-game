<?php

declare(strict_types=1);

namespace App\UI\Http;

use App\Domain\Entity\HangmanGame;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class CreateGameActionResponder
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function handle(Uuid $id, HangmanGame $game): JsonResponse
    {
        return new JsonResponse(
            array_merge(
                [
                    'id' => $id->toRfc4122(),
                ],
                $this->serializer->normalize($game, 'json')
            ),
            Response::HTTP_OK
        );
    }
}