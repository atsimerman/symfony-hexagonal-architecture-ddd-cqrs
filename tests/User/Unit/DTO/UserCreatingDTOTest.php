<?php

declare(strict_types=1);

namespace App\Tests\User\Unit\DTO;

use App\User\Presentation\DTO\UserCreatingDTO;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserCreatingDTOTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        // Boot the Symfony kernel for the test environment
        self::bootKernel();

        // Retrieve the ValidatorInterface service from the container
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    public function testValidDTO(): void
    {
        // Arrange: Create a UserCreatingDTO with valid data
        $dto = new UserCreatingDTO(
            email: 'user@example.com',
            password: 'B2IO+~8p`$M`_uA+',
            name: 'User Example'
        );

        // Act: Validate the DTO
        $errors = $this->validator->validate($dto);

        // Assert: Ensure no validation errors are returned
        $this->assertCount(0, $errors);
    }

    public function testInvalidEmail(): void
    {
        // Arrange: Create a UserCreatingDTO with an invalid email
        $dto = new UserCreatingDTO(
            email: 'invalid-email',
            password: 'B2IO+~8p`$M`_uA+',
            name: 'User Example'
        );

        // Act: Validate the DTO
        $errors = $this->validator->validate($dto);

        // Assert: Ensure validation errors are returned and check the error message
        $this->assertNotCount(0, $errors);
        $this->assertSame('This value is not a valid email address.', $errors[0]->getMessage());
    }

    public function testEmptyEmail(): void
    {
        // Arrange: Create a UserCreatingDTO with an empty email
        $dto = new UserCreatingDTO(
            email: '',
            password: 'B2IO+~8p`$M`_uA+',
            name: 'User Example'
        );

        // Act: Validate the DTO
        $errors = $this->validator->validate($dto);

        // Assert: Ensure validation errors are returned and check the error message
        $this->assertNotCount(0, $errors);
        $this->assertSame('This value should not be blank.', $errors[0]->getMessage());
    }

    public function testShortPassword(): void
    {
        // Arrange: Create a UserCreatingDTO with a short password
        $dto = new UserCreatingDTO(
            email: 'user@example.com',
            password: 'short',
            name: 'User Example'
        );

        // Act: Validate the DTO
        $errors = $this->validator->validate($dto);

        // Assert: Ensure validation errors are returned and check the error message
        $this->assertNotCount(0, $errors);
        $this->assertSame('This value is too short. It should have 8 characters or more.', $errors[0]->getMessage());
    }

    public function testWeakPassword(): void
    {
        // Arrange: Create a UserCreatingDTO with a weak password
        $dto = new UserCreatingDTO(
            email: 'user@example.com',
            password: 'weak_password',
            name: 'User Example'
        );

        // Act: Validate the DTO
        $errors = $this->validator->validate($dto);

        // Assert: Ensure validation errors are returned and check the error message
        $this->assertNotCount(0, $errors);
        $this->assertSame('The password strength is too low. Please use a stronger password.', $errors[0]->getMessage());
    }

    public function testEmptyName(): void
    {
        // Arrange: Create a UserCreatingDTO with an empty name
        $dto = new UserCreatingDTO(
            email: 'user@example.com',
            password: 'B2IO+~8p`$M`_uA+',
            name: ''
        );

        // Act: Validate the DTO
        $errors = $this->validator->validate($dto);

        // Assert: Ensure validation errors are returned and check the error message
        $this->assertNotCount(0, $errors);
        $this->assertSame('This value should not be blank.', $errors[0]->getMessage());
    }
}
