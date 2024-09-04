<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\FrameworkInfrastructure\Domain\Event\EventInterface;
use App\User\Domain\Entity\UserEmailConfirmationToken;

readonly class EmailConfirmationTokenCreated implements EventInterface
{
    public function __construct(private UserEmailConfirmationToken $userEmailConfirmationToken)
    {
    }

    public function getUserEmailConfirmationToken(): UserEmailConfirmationToken
    {
        return $this->userEmailConfirmationToken;
    }
}
