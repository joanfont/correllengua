<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Serializer\Normalizer;

use App\Domain\DTO\Common\Cursor;

use function assert;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class CursorNormalizer implements NormalizerInterface
{
    public function normalize(mixed $data, ?string $format = null, array $context = []): string
    {
        assert($data instanceof Cursor);

        return $data->encode();
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Cursor;
    }

    /**
     * @return array<class-string, bool>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [Cursor::class => true];
    }
}
