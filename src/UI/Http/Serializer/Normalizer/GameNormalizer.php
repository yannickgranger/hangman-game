<?php

declare(strict_types=1);

namespace App\UI\Http\Serializer\Normalizer;

use App\Domain\Entity\HangmanGame;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GameNormalizer implements NormalizerInterface
{
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        /** @var HangmanGame $object */
        return [
            'id' => $object->getId(),
            'word' => $object->getWord()->toArray(), /* here could make a WordNormalizer, would be cleaner */
            'remaining_attempts' => $object->getRemainingAttempts(),
            'max_attempts' => $object->getMaxAttempts(),
            'difficulty' => $object->getDifficulty()
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof HangmanGame;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            HangmanGame::class => true
        ];
    }
}
