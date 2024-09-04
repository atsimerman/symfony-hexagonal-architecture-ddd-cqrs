<?php

declare(strict_types=1);

namespace App\Tests\User\Unit\UseCase;

use App\FrameworkInfrastructure\Domain\Command\CommandBus;
use App\FrameworkInfrastructure\Domain\Command\CommandInterface;
use App\User\Domain\Command\CreateUserCommand;
use App\User\Domain\UseCase\CreateUser;
use App\User\Presentation\DTO\UserCreatingDTO;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateUserTest extends TestCase
{
    private CommandBus&MockObject $commandBusMock;
    private CreateUser $createUser;

    protected function setUp(): void
    {
        // Mock the CommandBus
        $this->commandBusMock = $this->createMock(CommandBus::class);

        // Instantiate the CreateUser use case with the mocked CommandBus
        $this->createUser = new CreateUser($this->commandBusMock);
    }

    public function testExecuteDispatchesCreateUserCommand(): void
    {
        // Arrange: Prepare the DTO with the test data
        $dto = new UserCreatingDTO(
            email: 'user@example.com',
            password: 'B2IO+~8p`$M`_uA+',
            name: 'User Example'
        );

        // Expectation: The command bus should receive a dispatch call with CreateUserCommand
        $this->commandBusMock->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->callback(function (CommandInterface $command) use ($dto) {
                    if (!$command instanceof CreateUserCommand) {
                        return false;
                    }

                    $user = $command->user;

                    return $user->getEmail() === $dto->email
                        && $user->getPlainPassword() === $dto->password
                        && $user->getName() === $dto->name;
                })
            );

        // Act: Execute the use case
        $this->createUser->execute($dto);
    }
}
