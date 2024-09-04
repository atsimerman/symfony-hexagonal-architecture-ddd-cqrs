<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Security;

use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @implements UserProviderInterface<SecurityUser>
 */
readonly class SecurityUserProvider implements UserProviderInterface
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        throw new \RuntimeException('The API is stateless. Check your configuration if this method is used.');
    }

    public function supportsClass(string $class): bool
    {
        return SecurityUser::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userRepository->findOneByEmail($identifier);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return new SecurityUser($user);
    }
}
