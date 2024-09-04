<?php

declare(strict_types=1);

namespace App\User\Infrastructure\EventListener;

use App\User\Domain\Event\EmailConfirmationTokenCreated;
use App\User\Infrastructure\Message\SendEmailConfirmationMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SendEmailConfirmationListener implements EventSubscriberInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            EmailConfirmationTokenCreated::class => 'onEmailConfirmationTokenCreated',
        ];
    }

    public function onEmailConfirmationTokenCreated(EmailConfirmationTokenCreated $event): void
    {
        $this->messageBus->dispatch(new SendEmailConfirmationMessage(
            $event->getUserEmailConfirmationToken()->getId()
        ));
    }
}
