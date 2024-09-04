<?php

declare(strict_types=1);

namespace App\FrameworkInfrastructure\Infrastructure\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ProblemNormalizer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;

final class ExceptionNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    public function __construct(private readonly ProblemNormalizer $problemNormalizer)
    {
    }

    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->problemNormalizer->setSerializer($serializer);
    }

    public function getSupportedTypes(?string $format): array
    {
        return $this->problemNormalizer->getSupportedTypes($format);
    }

    /**
     * @param array<string, mixed> $context
     * @return array<string, mixed>
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        $data = $this->problemNormalizer->normalize($object, $format, $context);

        if (isset($data['violations'])) {
            $data['errors'] = array_map(
                static fn ($violation) => ['property' => $violation['propertyPath'], 'title' => $violation['title']],
                $data['violations']
            );

            unset($data['violations']);
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $this->problemNormalizer->supportsNormalization($data, $format, $context);
    }
}
