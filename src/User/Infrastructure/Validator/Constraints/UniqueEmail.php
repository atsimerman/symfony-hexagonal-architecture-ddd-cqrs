<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class UniqueEmail extends Constraint
{
    public string $message = 'The email "{{ value }}" is already used.';
}
