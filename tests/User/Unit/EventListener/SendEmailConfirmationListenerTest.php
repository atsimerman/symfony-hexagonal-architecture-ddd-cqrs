<?php

declare(strict_types=1);

namespace App\Tests\User\Unit\EventListener;

use App\User\Domain\Entity\UserEmailConfirmationToken;
use App\User\Domain\Event\EmailConfirmationTokenCreated;
use App\User\Infrastructure\EventListener\SendEmailConfirmationListener;
use App\User\Infrastructure\Message\SendEmailConfirmationMessage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class SendEmailConfirmationListenerTest extends TestCase
{
    private MessageBusInterface&MockObject $messageBus;
    private SendEmailConfirmationListener $listener;

    protected function setUp(): void
    {
        // Initialize the mock message bus
        $this->messageBus = $this->createMock(MessageBusInterface::class);

        // Instantiate the listener with the mock message bus
        $this->listener = new SendEmailConfirmationListener($this->messageBus);
    }

    public function testOnEmailConfirmationTokenCreated(): void
    {
        // Arrange: Create a mock UserEmailConfirmationToken and simulate its behavior
        $userEmailConfirmationToken = $this->createMock(UserEmailConfirmationToken::class);
        $userEmailConfirmationToken->method('getId')->willReturn('token-id');

        // Prepare the event with the mock token
        $event = new EmailConfirmationTokenCreated($userEmailConfirmationToken);

        // Create the expected message that should be dispatched
        $message = new SendEmailConfirmationMessage('token-id');

        // Assert: Set the expectation that the message bus will dispatch the correct message
        $this->messageBus->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo($message))
            ->willReturn(new Envelope($message)); // Return an Envelope wrapping the message

        // Act: Trigger the listener with the event
        $this->listener->onEmailConfirmationTokenCreated($event);
    }
}
