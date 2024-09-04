<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Generator;

class TokenGenerator
{
    public function generate(): string
    {
        return bin2hex(random_bytes(16));
    }
}
