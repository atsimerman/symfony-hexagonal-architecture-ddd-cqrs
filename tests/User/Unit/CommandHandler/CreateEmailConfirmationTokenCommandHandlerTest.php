<?php

declare(strict_types=1);

namespace App\Tests\User\Unit\CommandHandler;

use App\FrameworkInfrastructure\Domain\Event\DispatcherManagerInterface;
use App\FrameworkInfrastructure\Domain\Repository\PersisterManagerInterface;
use App\User\Domain\Command\CreateEmailConfirmationTokenCommand;
use App\User\Domain\Entity\User;
use App\User\Domain\Entity\UserEmailConfirmationToken;
use App\User\Domain\Event\EmailConfirmationTokenCreated;
use App\User\Infrastructure\CommandHandler\CreateEmailConfirmationTokenCommandHandler;
use App\User\Infrastructure\Generator\TokenGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateEmailConfirmationTokenCommandHandlerTest extends TestCase
{
    private TokenGenerator&MockObject $tokenGenerator;
    private PersisterManagerInterface|MockObject $persisterManager;
    private DispatcherManagerInterface|MockObject $dispatcherManager;
    private CreateEmailConfirmationTokenCommandHandler $handler;

    protected function setUp(): void
    {
        // Mock the dependencies
        $this->tokenGenerator    = $this->createMock(TokenGenerator::class);
        $this->persisterManager  = $this->createMock(PersisterManagerInterface::class);
        $this->dispatcherManager = $this->createMock(DispatcherManagerInterface::class);

        // Instantiate the handler with the mocked dependencies
        $this->handler = new CreateEmailConfirmationTokenCommandHandler(
            $this->tokenGenerator,
            $this->persisterManager,
            $this->dispatcherManager
        );
    }

    public function testHandleCommandSuccessfully(): void
    {
        // Arrange: Prepare a user and command
        $user    = $this->createMock(User::class);
        $command = new CreateEmailConfirmationTokenCommand($user);

        // Mock the token generator to return a specific token
        $this->tokenGenerator
            ->method('generate')
            ->willReturn('generated-token');

        // Assert that the persister manager's save method is called once
        $this->persisterManager
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(UserEmailConfirmationToken::class));

        // Assert that the dispatcher manager's dispatch method is called once
        $this->dispatcherManager
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(EmailConfirmationTokenCreated::class));

        // Act: Handle the command
        $this->handler->__invoke($command);
    }
}
