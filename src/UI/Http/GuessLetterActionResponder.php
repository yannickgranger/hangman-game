<?php

declare(strict_types=1);

namespace App\UI\Http;

use App\Domain\Entity\HangmanGame;
use App\Domain\ValueObject\Letter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class GuessLetterActionResponder
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function handle(
        HangmanGame $game,
        Letter $letter,
        bool $guessStatus
    ): JsonResponse
    {
        if($game->isGameOver()) {
            return new JsonResponse($game->getResultMessage());
        }

        return new JsonResponse([
            'game' => $this->serializer->normalize($game),
            'letter' => $letter->getValue(),
            'feedback' => $game->getFeedback($letter, $guessStatus)
        ],Response::HTTP_OK
        );
    }

    public function handleException(\Exception $e)
    {
        return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
    }
}
