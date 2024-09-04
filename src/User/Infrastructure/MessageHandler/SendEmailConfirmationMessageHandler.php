<?php

declare(strict_types=1);

namespace App\User\Infrastructure\MessageHandler;

use App\User\Infrastructure\Email\UserRegistrationConfirmationEmail;
use App\User\Infrastructure\Message\SendEmailConfirmationMessage;
use App\User\Infrastructure\Repository\UserEmailConfirmationTokenRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class SendEmailConfirmationMessageHandler
{
    public function __construct(
        private MailerInterface $mailer,
        private UserEmailConfirmationTokenRepository $userEmailConfirmationTokenRepository
    ) {
    }

    public function __invoke(SendEmailConfirmationMessage $message): void
    {
        $userEmailConfirmationToken = $this->userEmailConfirmationTokenRepository->find(
            $message->getUserEmailConfirmationTokenId()
        );

        $this->mailer->send(new UserRegistrationConfirmationEmail($userEmailConfirmationToken));
    }
}
