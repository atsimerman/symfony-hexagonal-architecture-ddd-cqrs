<?php

declare(strict_types=1);

namespace App\User\Infrastructure\EventListener;

use App\FrameworkInfrastructure\Domain\Command\CommandBus;
use App\User\Domain\Command\CreateEmailConfirmationTokenCommand;
use App\User\Domain\Event\UserRegistered;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegisteredListener implements EventSubscriberInterface
{
    public function __construct(
        private CommandBus $commandDispatcher,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegistered::class => 'onUserRegistered',
        ];
    }

    public function onUserRegistered(UserRegistered $event): void
    {
        $user = $event->getUser();
        $this->commandDispatcher->dispatch(new CreateEmailConfirmationTokenCommand($user));
    }
}
