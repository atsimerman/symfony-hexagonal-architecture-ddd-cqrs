<?php

declare(strict_types=1);

namespace App\Tests\User\Unit\CommandHandler;

use App\User\Domain\Entity\User;
use App\User\Domain\Entity\UserEmailConfirmationToken;
use App\User\Infrastructure\Email\UserRegistrationConfirmationEmail;
use App\User\Infrastructure\Message\SendEmailConfirmationMessage;
use App\User\Infrastructure\MessageHandler\SendEmailConfirmationMessageHandler;
use App\User\Infrastructure\Repository\UserEmailConfirmationTokenRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;

class SendEmailConfirmationMessageHandlerTest extends TestCase
{
    private MailerInterface&MockObject $mailer;
    private UserEmailConfirmationTokenRepository&MockObject $userEmailConfirmationTokenRepository;
    private SendEmailConfirmationMessageHandler $handler;

    protected function setUp(): void
    {
        // Mock the dependencies
        $this->mailer                               = $this->createMock(MailerInterface::class);
        $this->userEmailConfirmationTokenRepository = $this->createMock(UserEmailConfirmationTokenRepository::class);

        // Instantiate the handler with the mocked dependencies
        $this->handler = new SendEmailConfirmationMessageHandler(
            $this->mailer,
            $this->userEmailConfirmationTokenRepository
        );
    }

    public function testHandleMessageSuccessfully(): void
    {
        // Arrange: Prepare a user and a message
        $user = $this->createMock(User::class);
        $user->method('getEmail')->willReturn('user@example.com');

        $userEmailConfirmationToken = $this->createMock(UserEmailConfirmationToken::class);
        $userEmailConfirmationToken->method('getUser')->willReturn($user);

        $this->userEmailConfirmationTokenRepository
            ->method('find')
            ->willReturn($userEmailConfirmationToken);

        $message = new SendEmailConfirmationMessage('token-id');

        // Assert that the mailer's send method is called once with a specific email instance
        $this->mailer->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf(UserRegistrationConfirmationEmail::class));

        // Act: Handle the message
        $this->handler->__invoke($message);
    }

    public function testHandleMessageWithNullEmail(): void
    {
        // Arrange: Prepare a user and a message
        $user = $this->createMock(User::class);
        $user->method('getEmail')->willReturn(null); // Simulate null email scenario

        $userEmailConfirmationToken = $this->createMock(UserEmailConfirmationToken::class);
        $userEmailConfirmationToken->method('getUser')->willReturn($user);

        $this->userEmailConfirmationTokenRepository
            ->method('find')
            ->willReturn($userEmailConfirmationToken);

        $message = new SendEmailConfirmationMessage('token-id');

        // Expect that the email will NOT be sent because email is null
        $this->mailer->expects($this->never())
            ->method('send');

        // Act & Assert
        $this->expectException(\TypeError::class); // Expect a TypeError due to null email
        $this->handler->__invoke($message);
    }
}
