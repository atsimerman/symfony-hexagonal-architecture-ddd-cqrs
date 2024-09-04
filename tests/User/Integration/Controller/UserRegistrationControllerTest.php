<?php

declare(strict_types=1);

namespace App\Tests\User\Integration\Controller;

use App\User\Domain\Entity\User;
use App\User\Infrastructure\Repository\UserEmailConfirmationTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRegistrationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;
    private UserEmailConfirmationTokenRepository $tokenRepository;

    protected function setUp(): void
    {
        // Initialize the test client for making HTTP requests
        $this->client = static::createClient();

        // Retrieve the entity manager from the service container for database operations
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        // Retrieve the token repository for interacting with email confirmation tokens
        $this->tokenRepository = static::getContainer()->get(UserEmailConfirmationTokenRepository::class);
    }

    public function testSuccessfulRegistrationFlow(): void
    {
        // Arrange: Define the valid payload
        $payload = [
            'email'    => 'user@example.com',
            'password' => 'B2IO+~8p`$M`_uA+',
            'name'     => 'User Example',
        ];

        // Act: Send a POST request to the /api/registration endpoint
        $this->client->jsonRequest('POST', '/api/registration', $payload);

        // Assert: Check the response status code
        self::assertResponseStatusCodeSame(201);

        // Assert: Check that exactly one email has been sent and it is queued
        self::assertEmailCount(1);
        self::assertEmailIsQueued(self::getMailerEvent());

        // Retrieve the sent email and assert its properties
        $email = self::getMailerMessage();
        self::assertEmailHasHeader($email, 'To');
        self::assertEmailHeaderSame($email, 'To', 'user@example.com');
        self::assertEmailTextBodyContains($email, 'User Example');

        // Assert: Verify that the user has been created in the database
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'user@example.com']);
        $this->assertNotNull($user);

        // Assert: Verify that an email confirmation token has been generated for the user
        $token = $this->tokenRepository->findOneBy(['user' => $user]);
        $this->assertNotNull($token);

        // Assert: Check that the token expiration time is less than 15 minutes from now
        $this->assertLessThan(new \DateTime('+15 minutes'), $token->getExpiresAt());
    }

    public function testInvalidRegistrationDataFlow(): void
    {
        // Arrange: Prepare invalid payload
        $payload = [
            'email'    => 'invalid-email',
            'password' => 'short',
            'name'     => '',
        ];

        // Act: Send a POST request with the invalid data
        $this->client->jsonRequest('POST', '/api/registration', $payload);

        // Assert: Verify the HTTP response status code is 422 (Unprocessable Entity)
        self::assertResponseStatusCodeSame(422);

        // Assert: Verify that no user was created with the invalid email
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'invalid-email']);
        $this->assertNull($user);

        // Assert: Verify that no email confirmation token was generated
        $token = $this->tokenRepository->findOneBy(['user' => $user]);
        $this->assertNull($token);
    }
}
