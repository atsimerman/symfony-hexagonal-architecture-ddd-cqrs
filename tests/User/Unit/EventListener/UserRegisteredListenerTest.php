<?php

declare(strict_types=1);

namespace App\Tests\User\Unit\EventListener;

use App\FrameworkInfrastructure\Domain\Command\CommandBus;
use App\User\Domain\Command\CreateEmailConfirmationTokenCommand;
use App\User\Domain\Entity\User;
use App\User\Domain\Event\UserRegistered;
use App\User\Infrastructure\EventListener\UserRegisteredListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserRegisteredListenerTest extends TestCase
{
    private CommandBus&MockObject $commandBus;
    private UserRegisteredListener $listener;

    protected function setUp(): void
    {
        // Initialize the mock command bus for dispatching commands
        $this->commandBus = $this->createMock(CommandBus::class);

        // Instantiate the listener with the mock command bus
        $this->listener   = new UserRegisteredListener($this->commandBus);
    }

    public function testOnUserRegistered(): void
    {
        // Arrange: Create a mock User entity and prepare the UserRegistered event
        $user  = $this->createMock(User::class);
        $event = new UserRegistered($user);

        // Create the expected command that should be dispatched
        $expectedCommand = $this->isInstanceOf(CreateEmailConfirmationTokenCommand::class);

        // Assert: Set the expectation that the command bus will dispatch the correct command
        $this->commandBus->expects($this->once())
            ->method('dispatch')
            ->with($expectedCommand);

        // Act: Trigger the listener method with the UserRegistered event
        $this->listener->onUserRegistered($event);
    }
}
