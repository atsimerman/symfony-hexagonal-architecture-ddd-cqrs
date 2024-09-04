<?php

declare(strict_types=1);

namespace App\User\Infrastructure\CommandHandler;

use App\FrameworkInfrastructure\Domain\Command\CommandHandlerInterface;
use App\FrameworkInfrastructure\Domain\Event\DispatcherManagerInterface;
use App\FrameworkInfrastructure\Domain\Repository\PersisterManagerInterface;
use App\User\Domain\Command\CreateUserCommand;
use App\User\Domain\Event\UserRegistered;
use App\User\Infrastructure\Security\SecurityUser;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class CreateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private PersisterManagerInterface $persisterManager,
        private DispatcherManagerInterface $dispatcherManager
    ) {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $user = $command->user;
        $user->setPassword($this->passwordHasher->hashPassword(new SecurityUser($user), $user->getPlainPassword()));
        $user->clearSensitiveData();

        $this->persisterManager->save($user);

        $this->dispatcherManager->dispatch(new UserRegistered($user));
    }
}
