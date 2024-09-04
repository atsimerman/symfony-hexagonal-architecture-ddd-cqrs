<?php

declare(strict_types=1);

namespace App\FrameworkInfrastructure\Domain\Event;

interface DispatcherManagerInterface
{
    public function dispatch(EventInterface $event): void;
}
