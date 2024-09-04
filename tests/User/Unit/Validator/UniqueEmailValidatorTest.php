<?php

declare(strict_types=1);

namespace App\Tests\User\Unit\Validator;

use App\User\Infrastructure\Repository\UserRepository;
use App\User\Infrastructure\Validator\Constraints\UniqueEmail;
use App\User\Infrastructure\Validator\Constraints\UniqueEmailValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @extends ConstraintValidatorTestCase<UniqueEmailValidator>
 */
class UniqueEmailValidatorTest extends ConstraintValidatorTestCase
{
    private UserRepository&MockObject $userRepository;

    protected function createValidator(): ConstraintValidatorInterface
    {
        // Mock the UserRepository to simulate database interactions
        $this->userRepository = $this->createMock(UserRepository::class);

        // Return an instance of the UniqueEmailValidator with the mocked UserRepository
        return new UniqueEmailValidator($this->userRepository);
    }

    public function testEmailIsUnique(): void
    {
        // Arrange: Set up the mock repository to return false, indicating the email is not used
        $this->userRepository
            ->method('isEmailUsed')
            ->willReturn(false);

        // Act: Validate the email with the UniqueEmail constraint
        $this->validator->validate('unique@example.com', new UniqueEmail());

        // Assert: Ensure that no violation is raised, meaning the email is considered unique
        $this->assertNoViolation();
    }

    public function testEmailIsNotUnique(): void
    {
        // Arrange: Set up the mock repository to return true, indicating the email is already used
        $this->userRepository
            ->method('isEmailUsed')
            ->willReturn(true);

        // Act: Validate the email with the UniqueEmail constraint
        $this->validator->validate('used@example.com', new UniqueEmail());

        // Assert: Ensure that a violation is raised with the appropriate error message
        $this->buildViolation('The email "{{ value }}" is already used.')
            ->setParameter('{{ value }}', 'used@example.com')
            ->assertRaised();
    }
}
