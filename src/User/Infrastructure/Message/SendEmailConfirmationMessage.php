<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Message;

use App\FrameworkInfrastructure\Domain\Message\AsyncMessageInterface;

readonly class SendEmailConfirmationMessage implements AsyncMessageInterface
{
    public function __construct(
        private string $userEmailConfirmationTokenId,
    ) {
    }

    public function getUserEmailConfirmationTokenId(): string
    {
        return $this->userEmailConfirmationTokenId;
    }
}
