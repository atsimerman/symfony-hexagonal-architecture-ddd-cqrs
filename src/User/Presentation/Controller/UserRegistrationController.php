<?php

declare(strict_types=1);

namespace App\User\Presentation\Controller;

use App\User\Domain\UseCase\CreateUser;
use App\User\Presentation\DTO\UserCreatingDTO;
use App\User\Presentation\ValueObject\UserRegistrationResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/registration', name: 'api_registration', methods: ['POST'])]
class UserRegistrationController extends AbstractController
{
    public function __construct(private readonly CreateUser $createUser)
    {
    }

    public function __invoke(#[MapRequestPayload] UserCreatingDTO $userCreatingDTO): JsonResponse
    {
        $this->createUser->execute($userCreatingDTO);

        $response = new UserRegistrationResponse();

        return $this->json($response->toArray(), $response->getHttpStatusCode());
    }
}
