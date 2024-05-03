<?php

declare(strict_types=1);

namespace App\UI\Http\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GameNormalizer implements NormalizerInterface
{
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        // TODO: Implement normalize() method.
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        // TODO: Implement supportsNormalization() method.
    }

    public function getSupportedTypes(?string $format): array
    {
        // TODO: Implement getSupportedTypes() method.
    }
}
