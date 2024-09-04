<?php

declare(strict_types=1);

namespace App\FrameworkInfrastructure\Domain\Command;

use Symfony\Component\Messenger\Stamp\ValidationStamp;

interface CommandBus
{
    /**
     * @param ValidationStamp[] $stamps
     */
    public function dispatch(CommandInterface $command, array $stamps = []): void;
}
