<?php

namespace App\UI\Http\Serializer\Normalizer;

use App\Domain\Entity\Player;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PlayerNormalizer implements NormalizerInterface
{
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        /* @var Player $object */
        return [
            'id' => $object->getId(),
            'username' => $object->getUsername(),
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Player;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Player::class => true,
        ];
    }
}
