<?php

declare(strict_types=1);

namespace App\UI\Http;

use App\Domain\Entity\HangmanGame;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class CreateGameActionResponder
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function handle(HangmanGame $game): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->normalize($game, 'json'),
            Response::HTTP_OK
        );
    }
}
