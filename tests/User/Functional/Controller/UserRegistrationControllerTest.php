<?php

declare(strict_types=1);

namespace App\Tests\User\Functional\Controller;

use App\Tests\TestCase\AbstractApiTestCase;
use App\User\Presentation\ValueObject\UserRegistrationResponse;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class UserRegistrationControllerTest extends AbstractApiTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        // Initialize the test client to simulate HTTP requests
        $this->client = static::createClient();
    }

    public function testSuccessfulUserRegistration(): void
    {
        // Arrange: Prepare a valid payload for user registration
        $payload = [
            'email'    => 'user@example.com',
            'password' => 'B2IO+~8p`$M`_uA+',
            'name'     => 'User Example',
        ];

        // Act: Send a POST request to the /api/registration endpoint with the valid payload
        $this->client->jsonRequest('POST', '/api/registration', $payload);

        // Assert: Verify that the response status code is the expected status code
        $expectedResponse = new UserRegistrationResponse();
        self::assertResponseStatusCodeSame($expectedResponse->getHttpStatusCode());

        // Decode the JSON response content into an associative array
        $content = $this->getDecodedJsonResponse($this->client->getResponse());

        // Assert: Ensure the response content matches the expected successful registration response
        self::assertSame($expectedResponse->toArray(), $content);
    }

    public function testInvalidUserRegistration(): void
    {
        // Arrange: Prepare an invalid payload with incorrect data
        $payload = [
            'email'    => 'invalid-email',
            'password' => 'short',
            'name'     => '',
        ];

        // Act: Send a POST request to the /api/registration endpoint with the invalid payload
        $this->client->jsonRequest('POST', '/api/registration', $payload);

        // Assert: Verify that the response status code is 422 (Unprocessable Entity) due to validation errors
        self::assertResponseStatusCodeSame(422);

        // Decode the JSON response content into an associative array
        $content = $this->getDecodedJsonResponse($this->client->getResponse());

        // Assert: Ensure the response contains a list of validation errors
        $this->assertArrayHasKey('errors', $content);

        // Assert: Validate that specific error messages are returned for each invalid field
        $this->assertContains(
            'This value is not a valid email address.',
            $this->getErrorMessages($content['errors'], 'email')
        );
        $this->assertContains(
            'This value is too short. It should have 8 characters or more.',
            $this->getErrorMessages($content['errors'], 'password')
        );
        $this->assertContains(
            'This value should not be blank.',
            $this->getErrorMessages($content['errors'], 'name')
        );
    }
}
