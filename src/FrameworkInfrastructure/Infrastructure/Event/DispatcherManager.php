<?php

declare(strict_types=1);

namespace App\FrameworkInfrastructure\Infrastructure\Event;

use App\FrameworkInfrastructure\Domain\Event\DispatcherManagerInterface;
use App\FrameworkInfrastructure\Domain\Event\EventInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly class DispatcherManager implements DispatcherManagerInterface
{
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function dispatch(EventInterface $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }
}
