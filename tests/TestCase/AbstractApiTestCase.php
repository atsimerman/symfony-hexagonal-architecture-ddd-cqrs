<?php

declare(strict_types=1);

namespace App\Tests\TestCase;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApiTestCase extends WebTestCase
{
    /**
     * @return array<string, mixed> the decoded JSON response content as an associative array
     */
    protected function getDecodedJsonResponse(Response $response): array
    {
        return json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param array<int, array{property: string, title: string}> $errors
     * @return string[] An array of error messages associated with the given property
     */
    protected function getErrorMessages(array $errors, string $property): array
    {
        $messages = [];
        foreach ($errors as $error) {
            if ($error['property'] === $property) {
                $messages[] = $error['title'];
            }
        }

        return $messages;
    }
}
