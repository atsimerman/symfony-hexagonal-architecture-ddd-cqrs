<?php

declare(strict_types=1);

namespace App\FrameworkInfrastructure\Infrastructure\ValueObject;

abstract class ApiResponse
{
    public function __construct(protected int $httpStatusCode)
    {
    }

    abstract public function getStatus(): string;

    abstract public function getMessage(): string;

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'status'  => $this->getStatus(),
            'message' => $this->getMessage(),
        ];
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
