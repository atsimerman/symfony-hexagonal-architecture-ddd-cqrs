<?php

declare(strict_types=1);

namespace App\FrameworkInfrastructure\Infrastructure\DataFixtures;

use App\User\Domain\Entity\User;
use App\User\Infrastructure\Security\SecurityUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setPassword($this->passwordHasher->hashPassword(new SecurityUser($user), 'B2IO+~8p`$M`_uA+'));
        $user->setName('Admin Example');
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        for ($i = 1000; $i <= 1010; ++$i) {
            $user = new User();
            $user->setEmail("user$i@example.com");
            $user->setName("User$i Example");
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword(new SecurityUser($user), bin2hex(random_bytes(8))));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
