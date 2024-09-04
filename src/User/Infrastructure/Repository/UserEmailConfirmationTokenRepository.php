<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Entity\UserEmailConfirmationToken;
use App\User\Domain\Repository\UserEmailConfirmationTokenRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserEmailConfirmationToken>
 */
class UserEmailConfirmationTokenRepository extends ServiceEntityRepository implements UserEmailConfirmationTokenRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserEmailConfirmationToken::class);
    }
}
