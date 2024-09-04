<?php

declare(strict_types=1);

namespace App\User\Domain\UseCase;

use App\FrameworkInfrastructure\Domain\Command\CommandBus;
use App\User\Domain\Command\CreateUserCommand;
use App\User\Domain\Entity\User;
use App\User\Presentation\DTO\UserCreatingDTO;

readonly class CreateUser
{
    public function __construct(private CommandBus $commandDispatcher)
    {
    }

    public function execute(UserCreatingDTO $userCreatingDTO): void
    {
        $user = new User();
        $user->setEmail($userCreatingDTO->email);
        $user->setPlainPassword($userCreatingDTO->password);
        $user->setName($userCreatingDTO->name);

        $command = new CreateUserCommand($user);

        $this->commandDispatcher->dispatch($command);
    }
}
