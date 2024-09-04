<?php

declare(strict_types=1);

namespace App\User\Presentation\ValueObject;

use App\FrameworkInfrastructure\Infrastructure\ValueObject\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class UserRegistrationResponse extends ApiResponse
{
    public const STATUS  = 'User created';
    public const MESSAGE = 'Successfully created user';

    public function __construct()
    {
        parent::__construct(Response::HTTP_CREATED);
    }

    public function getStatus(): string
    {
        return self::STATUS;
    }

    public function getMessage(): string
    {
        return self::MESSAGE;
    }
}
