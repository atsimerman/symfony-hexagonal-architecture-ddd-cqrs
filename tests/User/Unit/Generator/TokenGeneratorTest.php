<?php

declare(strict_types=1);

namespace App\Tests\User\Unit\Generator;

use App\User\Infrastructure\Generator\TokenGenerator;
use PHPUnit\Framework\TestCase;

class TokenGeneratorTest extends TestCase
{
    private TokenGenerator $tokenGenerator;

    protected function setUp(): void
    {
        // Instantiate the TokenGenerator to be tested
        $this->tokenGenerator = new TokenGenerator();
    }

    public function testGenerateTokenLength(): void
    {
        // Act: Generate a token using the TokenGenerator
        $token = $this->tokenGenerator->generate();

        // Assert: Ensure the generated token is a string
        $this->assertIsString($token, 'Token should be a string');

        // Assert: Verify that the token matches the expected 32-character hexadecimal format
        $this->assertMatchesRegularExpression(
            '/^[a-f0-9]{32}$/',
            $token,
            'Token should be a 32-character hexadecimal string'
        );
    }

    public function testGenerateTokenUniqueness(): void
    {
        // Arrange: Prepare an array to collect generated tokens
        $tokens = [];

        // Act: Generate multiple tokens and store them in the array
        for ($i = 0; $i < 1000; ++$i) {
            $token    = $this->tokenGenerator->generate();
            $tokens[] = $token;
        }

        // Assert: Check that all generated tokens are unique
        $uniqueTokens = array_unique($tokens);
        $this->assertCount(count($tokens), $uniqueTokens, 'Tokens should be unique');
    }
}
