<?php

declare(strict_types=1);

namespace App\User\Presentation\DTO;

use App\User\Infrastructure\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints as SymfonyAssert;
use Symfony\Component\Validator\Constraints\PasswordStrength;

final readonly class UserCreatingDTO
{
    public function __construct(
        #[SymfonyAssert\NotBlank]
        #[SymfonyAssert\Email]
        #[Assert\UniqueEmail]
        public string $email,

        #[SymfonyAssert\NotBlank]
        #[SymfonyAssert\Length(min: 8)]
        #[PasswordStrength(minScore: PasswordStrength::STRENGTH_MEDIUM)]
        public string $password,

        #[SymfonyAssert\NotBlank]
        public string $name,
    ) {
    }
}
