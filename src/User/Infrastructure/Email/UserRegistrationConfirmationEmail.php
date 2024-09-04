<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Email;

use App\User\Domain\Entity\UserEmailConfirmationToken;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class UserRegistrationConfirmationEmail extends TemplatedEmail
{
    public function __construct(UserEmailConfirmationToken $userEmailConfirmationToken)
    {
        parent::__construct();

        $user = $userEmailConfirmationToken->getUser();

        $this
            ->to($user->getEmail())
            ->subject('Please confirm your email address')
            ->htmlTemplate('email/user_registration_confirmation.html.twig')
            ->context([
                'name'           => $user->getName(),
                'token'          => $userEmailConfirmationToken->getToken(),
                'expirationDate' => $userEmailConfirmationToken->getExpiresAt(),
            ]);
    }
}
