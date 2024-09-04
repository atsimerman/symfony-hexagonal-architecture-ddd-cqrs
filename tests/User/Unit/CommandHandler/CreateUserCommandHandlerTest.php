<?php

declare(strict_types=1);

namespace App\Tests\User\Unit\CommandHandler;

use App\FrameworkInfrastructure\Domain\Event\DispatcherManagerInterface;
use App\FrameworkInfrastructure\Domain\Repository\PersisterManagerInterface;
use App\User\Domain\Command\CreateUserCommand;
use App\User\Domain\Entity\User;
use App\User\Domain\Event\UserRegistered;
use App\User\Infrastructure\CommandHandler\CreateUserCommandHandler;
use App\User\Infrastructure\Security\SecurityUser;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserCommandHandlerTest extends TestCase
{
    private UserPasswordHasherInterface&MockObject $passwordHasherMock;
    private PersisterManagerInterface&MockObject $persisterManagerMock;
    private DispatcherManagerInterface&MockObject $dispatcherManagerMock;
    private CreateUserCommandHandler $handler;

    protected function setUp(): void
    {
        // Create mock objects
        $this->passwordHasherMock    = $this->createMock(UserPasswordHasherInterface::class);
        $this->persisterManagerMock  = $this->createMock(PersisterManagerInterface::class);
        $this->dispatcherManagerMock = $this->createMock(DispatcherManagerInterface::class);

        // Instantiate the CreateUserCommandHandler with mocked dependencies
        $this->handler = new CreateUserCommandHandler(
            $this->passwordHasherMock,
            $this->persisterManagerMock,
            $this->dispatcherManagerMock
        );
    }

    public function testInvokeHandlesCreateUserCommandCorrectly(): void
    {
        // Arrange: Prepare a User entity with test data
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPlainPassword('B2IO+~8p`$M`_uA+');
        $user->setName('User Example');

        // Create the CreateUserCommand with the User entity
        $command = new CreateUserCommand($user);

        // Mock the behavior of the password hasher
        $hashedPassword = 'hashed_password';
        $this->passwordHasherMock->expects($this->once())
            ->method('hashPassword')
            ->with($this->isInstanceOf(SecurityUser::class), $user->getPlainPassword())
            ->willReturn($hashedPassword);

        // Mock the persister manager to expect a save call with the User entity
        $this->persisterManagerMock->expects($this->once())
            ->method('save')
            ->with($this->callback(function (User $savedUser) use ($hashedPassword) {
                // Ensure that the saved User has the hashed password and no plain password
                return $savedUser->getPassword() === $hashedPassword
                    && $savedUser->getPlainPassword() === null;
            }));

        // Mock the dispatcher to expect the UserRegistered event
        $this->dispatcherManagerMock->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function (UserRegistered $event) use ($user) {
                // Ensure that the dispatched event contains the correct User
                return $event->getUser() === $user;
            }));

        // Act: Invoke the command handler
        $this->handler->__invoke($command);
    }
}
