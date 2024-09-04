<?php

declare(strict_types=1);

namespace App\User\Infrastructure\CommandHandler;

use App\FrameworkInfrastructure\Domain\Command\CommandHandlerInterface;
use App\FrameworkInfrastructure\Domain\Event\DispatcherManagerInterface;
use App\FrameworkInfrastructure\Domain\Repository\PersisterManagerInterface;
use App\User\Domain\Command\CreateEmailConfirmationTokenCommand;
use App\User\Domain\Entity\UserEmailConfirmationToken;
use App\User\Domain\Event\EmailConfirmationTokenCreated;
use App\User\Infrastructure\Generator\TokenGenerator;
use Carbon\CarbonImmutable;

final readonly class CreateEmailConfirmationTokenCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TokenGenerator $tokenGenerator,
        private PersisterManagerInterface $persisterManager,
        private DispatcherManagerInterface $dispatcherManager,
    ) {
    }

    public function __invoke(CreateEmailConfirmationTokenCommand $command): void
    {
        $userEmailConfirmationToken = new UserEmailConfirmationToken();
        $userEmailConfirmationToken->setUser($command->user);
        $userEmailConfirmationToken->setToken($this->tokenGenerator->generate());
        $userEmailConfirmationToken->setExpiresAt(new CarbonImmutable(sprintf('now + %s minutes', 15)));

        $this->persisterManager->save($userEmailConfirmationToken);

        $this->dispatcherManager->dispatch(new EmailConfirmationTokenCreated($userEmailConfirmationToken));
    }
}
