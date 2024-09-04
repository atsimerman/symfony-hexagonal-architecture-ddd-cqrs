<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\FrameworkInfrastructure\Domain\Event\EventInterface;
use App\User\Domain\Entity\User;

readonly class UserRegistered implements EventInterface
{
    public function __construct(private User $user)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
