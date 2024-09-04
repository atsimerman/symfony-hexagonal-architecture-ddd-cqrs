<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\User\Domain\Entity\User;
use App\User\Infrastructure\Security\SecurityUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthenticationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $container            = static::getContainer();
        $this->entityManager  = $container->get(EntityManagerInterface::class);
        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);
    }

    /**
     * @return array<int, array<int, string>>
     */
    public function userProvider(): array
    {
        return [
            ['user@example.com', 'B2IO+~8p`$M`_uA+', 'User Example'],
        ];
    }

    /**
     * @dataProvider userProvider
     */
    public function testUserCanLogin(string $email, string $password, string $name): void
    {
        $this->createUser($email, $password, $name);

        $this->client->jsonRequest('POST', '/api/login', [
            'username' => $email,
            'password' => $password,
        ]);

        // assert that the response is successful
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = $this->client->getResponse();
        $content  = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        // assert that the response contains a token
        $this->assertArrayHasKey('token', $content);
    }

    public function testInvalidLogin(): void
    {
        $this->client->jsonRequest('POST', '/api/login', [
            'username' => 'user@example.com',
            'password' => 'wrong_password',
        ]);

        // assert that the response returns a 401 Unauthorized status
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    private function createUser(string $email, string $password, string $name): void
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword(new SecurityUser($user), $password));
        $user->setName($name);
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
