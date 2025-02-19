<?php

declare(strict_types=1);

namespace App\FrameworkInfrastructure\Domain\Repository;

interface PersisterManagerInterface
{
    public function save(object $entity, bool $flush = false): void;

    public function hardDelete(object $entity, bool $flush = false): void;
}
